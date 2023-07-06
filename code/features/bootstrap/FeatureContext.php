<?php



use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManager;
use Slim\App;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\Infrastructures\Services\AuthService;
use Travis\StreamingBackend\Infrastructures\Services\StreamService;

/**
 * Defines application features from the specific context.
 */
class FeatureContext  implements Context
{
    private App $app;
    private AuthService $auth_service;
    private StreamService $stream_service;
    private StreamRepositoryInterface $stream_repository;
    private User $user;
    private Stream $stream;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $this->app = $app();
        $this->auth_service = $this->app->getContainer()->get(AuthService::class);
        $this->stream_service = $this->app->getContainer()->get(StreamService::class);
        $this->stream_repository = $this->app->getContainer()->get(StreamRepositoryInterface::class);
        $container = $this->app->getContainer();
        $dotenv = Dotenv\Dotenv::createMutable(__DIR__.'/../../','.env.testing');
        $dotenv->load();
        exec("./vendor/bin/doctrine-migrations migrations:migrate first --db-configuration=migrations-db-test.php --no-interaction --quiet");
        exec("./vendor/bin/doctrine-migrations migrations:migrate --db-configuration=migrations-db-test.php --no-interaction --quiet");
        /* @var EntityManager $entity_manager */
        $entity_manager = $container->get(EntityManager::class);
        $sql = "INSERT INTO oauth_clients (identifier, name, client_id, client_secret, redirect_uri, provider, created_at, updated_at) VALUES ('app', 'app', '123', '123', null, null, '2023-07-05 18:24:01', '2023-07-05 18:24:05');" ;
        $query = $entity_manager->getConnection()->prepare($sql);
        $query->executeQuery();
    }


    /**
     * @Given /^There are user:$/
     */
    public function thereAreUser(TableNode $table): void
    {
        foreach ($table as $row) {
            $userVO = new \Travis\StreamingBackend\Domains\ValueObjects\UserVO(
                email: $row['email'],
                password: $row['password'],
                name: $row['name']
            );
            $this->user = $this->auth_service->register(userVO: $userVO);

        }
    }

    /**
     * @When /^I start streaming$/
     */
    public function iStartStreaming()
    {
        $this->stream = $this->stream_service->startStreaming(user: $this->user,title: 'test');
    }




    /**
     * @Then /^Streaming session should be started and recorded in database$/
     */
    public function streamingSessionShouldBeStartedAndRecordedInDatabase()
    {

        $current_stream = $this->stream_repository->findUserActiveStream($this->user);
        \PHPUnit\Framework\Assert::assertEquals($this->stream,$current_stream);
    }




}
