xing-repository (version 0.01.00)
===============

####The goal of the project is:

1. Isolate the data layer using [The Repository Pattern](http://msdn.microsoft.com/en-us/library/ff649690.aspx "The Repository Pattern")
2. Avoid the use of platform specific language such as MySQL (or framework specific language such as Doctrine's DQL) in your Model, Service, or Controller layers.  This is done by creating a Search Object that will become a part of the Model and can be used with any system that is segregated with the Repository Pattern defined within this project.
3. Avoid the use of strings and increase IntelliSense support.  The search object instance integrates with IntelliSense, which can help to spot errors before run-time.

###Try It Out
All you need is a PHP 5.3+ server that supports sqlite.  Setup this project in a web directory and run the _test.php file in the root directory.  The code that is in the Example/ folder can work as a guide until more complete documentation can be created.

###PURPOSE: String-less (object oriented) search against a single-repository.

The purpose of [The Repository Pattern](http://msdn.microsoft.com/en-us/library/ff649690.aspx "The Repository Pattern") is to abstract not just your database engine (like Doctrine does by default), but to abstract the entire database access layer.  The Repository Pattern does not replace a framework like Doctrine and can actually be used in conjunction with it to avoid Doctrine dependencies within your business layer.  Using this framework, the only concrete dependencies in your code are your entities and "search objects".  Your Repository instance and your Data Mapper can be easily substituted at run-time using the [Service Locator](http://msdn.microsoft.com/en-us/library/ff648968.aspx "Service Locator Pattern") (class \Xing\System\Locator).

The "search object" is an object oriented way to search your entities using [Method Chaining](http://en.wikipedia.org/wiki/Method_chaining "Method Chaining / Method Cascading") to create a [Fluent interface](http://en.wikipedia.org/wiki/Fluent_interface "Fluent Interface") to build your conditions.  The resulting search object gets passed to The Repository Layer where it gets processed, using a [Data Mapper](http://martinfowler.com/eaaCatalog/dataMapper.html "Data Mapper"), to return the list of entities that match the search object's conditions.

Using the Service Locator, you can easily change from using a MySQL or Sqlite Repository (included, though Sqlite is greatly under-tested), and create a Doctrine Repository that will convert your search object into DQL...all without changing anything in your business layer.

This all sounds very complex, but in practice it is quite simple and fast to set things up, and the separation of concerns can be well worth the investment, which many of us have learned through experience.

In any case, a search looks something like this:

	$repo = Locator::get('IRepository');
    $repo->search(
        UserSearch::instance()->Id->isIn(array(1,2,3))
            ->andThe()->Email->is('johndoe@example.com')
            ->allPreviousOr()
            ->Email->is('ninja@example.com')
    );

This would create a query with a WHERE something like:

	WHERE (UserId IS IN(1,2,3) AND Email = 'johndoe@example.com')
		OR Email = 'ninja@example.com'

The Service Locator finds the IRepository based on runtime configuration like the following:

	Locator::defineDependency('IRepository','/Namespace/To/Repository');

Most basic conditions can be created from a very simple search object, and the search object itself inherits from a base class that provides most of the functionality.  All you have to do to create the search object is create a class, define the available properties, and specify the Model object that the search object returns, e.g.

    /**
     * Xing-Repository Usage Example Search Object
     *
     * @copyright 2013 Kevin K. Nelson (xingcreative.com)
     * Licensed under the MIT license
     */
    namespace Example\Search {
        use Example\User;
        use Xing\Repository\AIntelliSearch;

        /**
         * Class UserSearch
         * @package Example\Search
         *
         * @property-read UserSearch $Id
         * @property-read UserSearch $Email
         * @property-read UserSearch $Username
         */
        class UserSearch extends AIntelliSearch {
            public static function instance() {
                return new self();
            }
            public function defineProperties() {
                $this->_properties->addRange(array('Id','Email','Username'));
            }
            public function getModelInstance() {
                return new User();
            }
        }
    }

Notice that in the phpDoc comment I define Id, Email, and Username as read-only properties that return the class instance, then in the defineProperties method, I define an array with those names.  The phpDoc comment is not required, but it tells the editor the property is available and that it returns the class instance so that the IntelliSense will work in an editor with IntelliSense.  The defineProperties method is a construct that allows you to encapsulate what properties a developer is able to search against so that you don't have to open up every field in the database to other developers or teams if that is not desired.

Anyway, in years of using search objects, I have not come across a case where I was not able to use a search object and needed to allow SQL code to be passed as a parameter.  Even extremely complex queries can be handled by customizing the Mapper class, which is not difficult...but that's a longer story to be explained in documentation.

Doctrine is, at least at this time, much more powerful and robust, but I like the Repository Pattern and search objects, so I've never had much inclination to use Doctrine.  Also, part of this is my love for .NET's LINQ, and this is my way of getting as close to being able to use LINQ in PHP as I've been able to accomplish.

The most important part of this repository framework, however, is that your only true dependency is your model: The search object and the model object.  The mappers and repositories are run-time swappable, which is the nature and purpose of the repository pattern.  With the Repository Pattern you can easily do some of the following:

- You could write a Repository class that converted these search objects to DQL and use Doctrine behind the scenes without your model ever needing to change.
- At run-time you can change your Repository or Mapper objects for Unit Tests or some other purpose.

In the code example above and in the Example module in the project itself, you have a search object "UserSearch" and your model object "User".  These are part of your unchanging model (meaning the model doesn't change based on how the data is accessed, not that you never change it).  Your code will become highly dependent on both of these objects as well as the EntityCollection that the IRepository::search() should always return.  Then, to ensure those are our only dependencies on our model, we utilize a simple dependency injection structure to map your model "User" object to a mapper like so:


	Locator::defineDependency( 'Example\User\Mapper',
        '\Example\Mapper\UserMapper');

The above is as simple as taking the namespace for your model: 'Example\User' and appending '\Mapper' to it for the first, key parameter.  Then, the second parameter defines the namespace for your actual mapper file.  This way, you can easily change your mapper at runtime to a mock object for unit testing or whatever you want.

If the column names of the database match the property names of your model object and you don't need to do any table JOINs, etc., in your query, then the only thing you need to do in your mapper file is define the table name and the primary key name.  If they don't match, or you need to do some JOINs with other tables, then you can define a column/property array map to map mismatched names and define your own buildQuery() method to override the default and add some JOINs.  I will try to cover this in the documentation more later.

###Current Weakness

- There is no [Unit of Work](http://www.martinfowler.com/eaaCatalog/unitOfWork.html "Unit of Work")
- It seems to me that the Data Mapper should actually be able to define which repository to use rather than the ServiceLocator.  E.g. IRepository should link to a ProxyRepository that loads up the mapper for the given model instance.  The mapper, which is already highly dependent on the data engine/service can then return the concrete repository instance that the proxy can hand the search object to.  This is fairly easy to do and won't break anything that currently exists, so I will probably implement this shortly.

###Multiple Repositories

Currently, if you get most of your data through MySQL, you would have the Service Locator return the SqlRepository when `Locator::get('IRepository')` is called, etc.  Then, if you get weather data through a REST service, you could create a REST repository and then inject that with WeatherRepository like so:

    $weatherRepo = Locator::get('WeatherRepository');

When creating a repository there is no need to use the same type of search objects that are built-in, but you should try to make your Repository implement the IRepository interface and only contain methods that are in the IRepository interface (or an extended interface you create), otherwise you are defeating part of the purpose of having such interfaces.