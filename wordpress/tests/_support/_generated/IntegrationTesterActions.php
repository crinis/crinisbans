<?php  //[STAMP] aa885fcf454257867ea12348d0510406
namespace _generated;

// This class was automatically generated by build task
// You should not change it manually as it will be overwritten on next build
// @codingStandardsIgnoreFile

use Helper\Integration;
use Codeception\Module\WPLoader;
use Codeception\Module\WPQueries;

trait IntegrationTesterActions
{
    /**
     * @return \Codeception\Scenario
     */
    abstract protected function getScenario();

    
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \Codeception\Module\WPLoader::ensureDbModuleCompat()
     */
    public function ensureDbModuleCompat() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('ensureDbModuleCompat', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Calls a list of user-defined actions needed in tests.
     * @see \Codeception\Module\WPLoader::bootstrapActions()
     */
    public function bootstrapActions() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('bootstrapActions', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \Codeception\Module\WPLoader::activatePlugins()
     */
    public function activatePlugins() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('activatePlugins', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Loads the plugins required by the test.
     * @see \Codeception\Module\WPLoader::loadPlugins()
     */
    public function loadPlugins() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('loadPlugins', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query was made during the test.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueries()
     */
    public function assertQueries($message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueries', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueries()
     */
    public function assertNotQueries($message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueries', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries have been made.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertCountQueries()
     */
    public function assertCountQueries($n, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertCountQueries', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least a query starting with the specified statement was made.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByStatement()
     */
    public function assertQueriesByStatement($statement, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByStatement', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query has been made by the specified class method.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $class
	 * @param string $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByMethod()
     */
    public function assertQueriesByMethod($class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries have been made by the specified class method.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByStatement()
     */
    public function assertNotQueriesByStatement($statement, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByStatement', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries starting with the specified statement were made.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByStatement()
     */
    public function assertQueriesCountByStatement($n, $statement, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByStatement', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries have been made by the specified class method.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param $class
	 * @param $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByMethod()
     */
    public function assertNotQueriesByMethod($class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries have been made by the specified class method.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $class
	 * @param string $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByMethod()
     */
    public function assertQueriesCountByMethod($n, $class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that queries were made by the specified function.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByFunction()
     */
    public function assertQueriesByFunction($function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made by the specified function.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByFunction()
     */
    public function assertNotQueriesByFunction($function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made by the specified function.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByFunction()
     */
    public function assertQueriesCountByFunction($n, $function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that queries were made by the specified class method starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $class
	 * @param string $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByStatementAndMethod()
     */
    public function assertQueriesByStatementAndMethod($statement, $class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByStatementAndMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made by the specified class method starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $class
	 * @param string $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByStatementAndMethod()
     */
    public function assertNotQueriesByStatementAndMethod($statement, $class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByStatementAndMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made by the specified class method starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $class
	 * @param string $method
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByStatementAndMethod()
     */
    public function assertQueriesCountByStatementAndMethod($n, $statement, $class, $method, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByStatementAndMethod', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that queries were made by the specified function starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByStatementAndFunction()
     */
    public function assertQueriesByStatementAndFunction($statement, $function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByStatementAndFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made by the specified function starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByStatementAndFunction()
     */
    public function assertNotQueriesByStatementAndFunction($statement, $function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByStatementAndFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made by the specified function starting with the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $function
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByStatementAndFunction()
     */
    public function assertQueriesCountByStatementAndFunction($n, $statement, $function, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByStatementAndFunction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query was made as a consequence of the specified action.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByAction()
     */
    public function assertQueriesByAction($action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made as a consequence of the specified action.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByAction()
     */
    public function assertNotQueriesByAction($action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made as a consequence of the specified action.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByAction()
     */
    public function assertQueriesCountByAction($n, $action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query was made as a consequence of the specified action containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByStatementAndAction()
     */
    public function assertQueriesByStatementAndAction($statement, $action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByStatementAndAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made as a consequence of the specified action containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByStatementAndAction()
     */
    public function assertNotQueriesByStatementAndAction($statement, $action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByStatementAndAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made as a consequence of the specified action containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $action The action name, e.g. 'init'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByStatementAndAction()
     */
    public function assertQueriesCountByStatementAndAction($n, $statement, $action, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByStatementAndAction', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query was made as a consequence of the specified filter.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByFilter()
     */
    public function assertQueriesByFilter($filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByFilter', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made as a consequence of the specified filter.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByFilter()
     */
    public function assertNotQueriesByFilter($filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByFilter', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made as a consequence of the specified filter.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByFilter()
     */
    public function assertQueriesCountByFilter($n, $filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByFilter', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that at least one query was made as a consequence of the specified filter containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                          Regular expressions must contain delimiters.
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesByStatementAndFilter()
     */
    public function assertQueriesByStatementAndFilter($statement, $filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesByStatementAndFilter', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that no queries were made as a consequence of the specified filter containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertNotQueriesByStatementAndFilter()
     */
    public function assertNotQueriesByStatementAndFilter($statement, $filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertNotQueriesByStatementAndFilter', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Asserts that n queries were made as a consequence of the specified filter containing the specified SQL statement.
	 *
	 * Queries generated by setUp, tearDown and factory methods are excluded by default.
	 *
	 * @param int $n The expected number of queries.
	 * @param string $statement A simple string the statement should start with or a valid regular expression.
	 *                           Regular expressions must contain delimiters.
	 * @param string $filter The filter name, e.g. 'posts_where'.
	 * @param string $message An optional message to override the default one.
     * @see \Codeception\Module\WPQueries::assertQueriesCountByStatementAndFilter()
     */
    public function assertQueriesCountByStatementAndFilter($n, $statement, $filter, $message = null) {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('assertQueriesCountByStatementAndFilter', func_get_args()));
    }
}
