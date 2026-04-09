<?php
declare(strict_types=1);

// src/DDDGenerator.php
namespace YourVendor\DDDGenerator;

class DDDGenerator
{
    private array $dddConfig = [];
    private array $eventsConfig = [];

    public function generate(string $appName, string $dddConfigPath, ?string $eventsConfigPath = null)
    {
        // Use current working directory as base for project output
        $basePath = getcwd() . "/$appName";

        // Загружаем основную конфигурацию ddd.json
        if (!file_exists($dddConfigPath)) {
            throw new \InvalidArgumentException("Config file not found: $dddConfigPath");
        }

        $dddContent = file_get_contents($dddConfigPath);
        $decoded = json_decode($dddContent, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(
                "ddd.json parse error: " . json_last_error_msg() . " in file $dddConfigPath"
            );
        }
        $this->dddConfig = $decoded;

        if (empty($this->dddConfig['aggregates']) && !isset($this->dddConfig['name'])) {
            throw new \InvalidArgumentException(
                "ddd.json error: 'aggregates' array is required. Example: {\"aggregates\": [{\"name\": \"Product\", ...}]}"
            );
        }

        // Загружаем конфигурацию событий events.json
        $this->eventsConfig = [];
        if ($eventsConfigPath !== null && file_exists($eventsConfigPath)) {
            $eventsContent = file_get_contents($eventsConfigPath);
            $decodedEvents = json_decode($eventsContent, true);
            if ($decodedEvents === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException(
                    "events.json parse error: " . json_last_error_msg() . " in file $eventsConfigPath"
                );
            }
            $this->eventsConfig = $decodedEvents;
            if (!isset($this->eventsConfig['subscribers']) || !is_array($this->eventsConfig['subscribers'])) {
                throw new \InvalidArgumentException(
                    "events.json error: 'subscribers' array is required. Example: {\"subscribers\": [{\"source\": \"Product\", \"event\": \"Created\", \"target\": \"Category\"}]}"
                );
            }
        }

        $aggregates = $this->dddConfig['aggregates'] ?? [$this->dddConfig];

        // Создаём Shared структуру
        $this->createDirectory("$basePath/src/Shared/Application/Command");
        $this->createDirectory("$basePath/src/Shared/Application/Event");
        $this->createDirectory("$basePath/src/Shared/Application/Query");

        // Генерируем для каждого агрегата
        foreach ($aggregates as $aggregate) {
            $this->processAggregate($basePath, $appName, $aggregate);
        }

        // Генерируем Event Subscribers (адаптеры между агрегатами)
        $this->generateEventSubscribers($basePath, $appName, $aggregates);

        // Генерируем composer.json
        $this->generateComposerJson($basePath, $appName, $aggregates);

        // Создаём tests directory
        $this->createDirectory("$basePath/tests");
    }

    private function processAggregate(string $basePath, string $appName, array $aggregate): void
    {
        $aggregateName = $aggregate['name'];
        $isAggregate = $aggregate['isAggregate'] ?? true;
        $aggregatePath = "$basePath/src/$aggregateName";

        $props = $this->parseProperties($aggregate['properties'] ?? []);

        // Domain
        $this->createDirectory("$aggregatePath/Domain/Entity");

        if ($isAggregate) {
            // Генерируем Aggregate (Entity)
            $this->generateEntity(
                "$aggregatePath/Domain/Entity/{$aggregateName}.php",
                $aggregateName,
                $props,
                $aggregate['events'] ?? []
            );

            // Генерируем базовые классы для Aggregate
            $this->generateFromTemplate(
                "$aggregatePath/Domain/Entity/Aggregate.php",
                __DIR__ . '/../templates/Aggregate.php.template',
                ['##Name##' => $aggregateName]
            );
            $this->generateFromTemplate(
                "$aggregatePath/Domain/Entity/EventTrait.php",
                __DIR__ . '/../templates/EventTrait.php.template',
                ['##Name##' => $aggregateName]
            );
        } else {
            // Генерируем обычную Entity (не Aggregate)
            $this->generateSimpleEntity(
                "$aggregatePath/Domain/Entity/{$aggregateName}.php",
                $aggregateName,
                $props
            );
        }

        // Value Objects
        $this->createDirectory("$aggregatePath/Domain/VO");
        $this->generateFromTemplate(
            "$aggregatePath/Domain/VO/ID.php",
            __DIR__ . '/../templates/ID.php.template',
            ['##Name##' => $aggregateName]
        );

        // Генерируем VOs из свойств
        foreach ($props as $prop) {
            if ($prop['isVO']) {
                $this->generateVO("$aggregatePath/Domain/VO/{$prop['type']}.php", $aggregateName, $prop['type']);
            }
        }

        // Exceptions
        $this->createDirectory("$aggregatePath/Domain/Exception/$aggregateName");
        $this->generateFromTemplate(
            "$aggregatePath/Domain/Exception/$aggregateName/{$aggregateName}EmptyNameException.php",
            __DIR__ . '/../templates/NameEmptyNameException.php.template',
            ['##Name##' => $aggregateName]
        );

        // Domain Events
        $events = $aggregate['events'] ?? ['Created', 'Renamed', 'Deleted', 'Archived', 'Reinstated'];
        $this->createDirectory("$aggregatePath/Domain/Event/$aggregateName");
        foreach ($events as $event) {
            $this->generateFromTemplate(
                "$aggregatePath/Domain/Event/$aggregateName/{$aggregateName}{$event}.php",
                __DIR__ . '/../templates/Name' . $event . '.php.template',
                ['##Name##' => $aggregateName]
            );
        }

        // Repository
        if ($isAggregate) {
            $this->createDirectory("$aggregatePath/Domain/Repository");
            $this->generateFromTemplate(
                "$aggregatePath/Domain/Repository/{$aggregateName}RepositoryInterface.php",
                __DIR__ . '/../templates/NameRepositoryInterface.php.template',
                ['##Name##' => $aggregateName]
            );
        }

        // Application Commands
        $commands = $aggregate['commands'] ?? ['Create', 'Archive', 'Delete', 'Reinstate', 'Rename'];
        foreach ($commands as $command) {
            $this->createDirectory("$aggregatePath/Application/$aggregateName/Command/$command");
            $this->generateFromTemplate(
                "$aggregatePath/Application/$aggregateName/Command/$command/{$command}{$aggregateName}Command.php",
                __DIR__ . '/../templates/' . $command . 'NameCommand.php.template',
                ['##Name##' => $aggregateName]
            );
            $this->generateFromTemplate(
                "$aggregatePath/Application/$aggregateName/Command/$command/{$command}{$aggregateName}CommandHandler.php",
                __DIR__ . '/../templates/' . $command . 'NameCommandHandler.php.template',
                ['##Name##' => $aggregateName]
            );
        }

        // Application Queries
        $queries = $aggregate['queries'] ?? ['GetAll', 'GetById', 'GetByName', 'Unique'];
        foreach ($queries as $query) {
            $this->createDirectory("$aggregatePath/Application/$aggregateName/Query/$query");
            $this->generateFromTemplate(
                "$aggregatePath/Application/$aggregateName/Query/$query/{$query}{$aggregateName}Query.php",
                __DIR__ . '/../templates/' . $query . '##Name##Query.php.template',
                ['##Name##' => $aggregateName]
            );
            $this->generateFromTemplate(
                "$aggregatePath/Application/$aggregateName/Query/$query/{$query}{$aggregateName}QueryHandler.php",
                __DIR__ . '/../templates/' . $query . '##Name##QueryHandler.php.template',
                ['##Name##' => $aggregateName]
            );
        }

        // Listeners (для локальных событий)
        $this->createDirectory("$aggregatePath/Application/$aggregateName/Listener");
        foreach ($events as $event) {
            $this->generateFromTemplate(
                "$aggregatePath/Application/$aggregateName/Listener/{$aggregateName}{$event}Listener.php",
                __DIR__ . '/../templates/Name' . $event . 'Listener.php.template',
                ['##Name##' => $aggregateName]
            );
        }

        // Infrastructure
        $this->createDirectory("$aggregatePath/Infrastructure/API");
        $this->createDirectory("$aggregatePath/Infrastructure/Persistance");
    }

    private function generateEventSubscribers(string $basePath, string $appName, array $aggregates): void
    {
        $subscribers = $this->eventsConfig['subscribers'] ?? [];

        foreach ($subscribers as $subscriber) {
            $sourceAggregate = $subscriber['source'];
            $targetAggregate = $subscriber['target'];
            $eventName = $subscriber['event'];

            // Создаём папку для адаптера в target агрегате
            $adapterPath = "$basePath/src/$targetAggregate/Application/$targetAggregate/Adapter";
            $this->createDirectory($adapterPath);

            $subscriberName = "{$eventName}{$sourceAggregate}To{$targetAggregate}Subscriber";
            $this->generateEventSubscriber(
                "$adapterPath/$subscriberName.php",
                $appName,
                $sourceAggregate,
                $targetAggregate,
                $eventName,
                $subscriberName
            );
        }
    }

    private function generateEventSubscriber(
        string $path,
        string $appName,
        string $sourceAggregate,
        string $targetAggregate,
        string $eventName,
        string $subscriberName
    ): void {
        $lines = [];
        $lines[] = '<?php';
        $lines[] = 'declare(strict_types=1);';
        $lines[] = '';
        $lines[] = 'namespace Medigi\\' . $targetAggregate . '\\Application\\' . $targetAggregate . '\\Adapter;';
        $lines[] = '';
        $lines[] = 'use Medigi\\' . $sourceAggregate . '\\Domain\\Event\\' . $sourceAggregate . '\\' . $sourceAggregate . $eventName . ';';
        $lines[] = 'use Medigi\\' . $targetAggregate . '\\Application\\' . $targetAggregate . '\\Command\\Create\\Create' . $targetAggregate . 'Command;';
        $lines[] = 'use Medigi\\' . $targetAggregate . '\\Application\\' . $targetAggregate . '\\Command\\Create\\Create' . $targetAggregate . 'CommandHandler;';
        $lines[] = '';
        $lines[] = '/**';
        $lines[] = ' * Adapter: subscribes to ' . $sourceAggregate . $eventName . ' and creates/updates ' . $targetAggregate;
        $lines[] = ' */';
        $lines[] = 'class ' . $subscriberName;
        $lines[] = '{';
        $lines[] = '    private Create' . $targetAggregate . 'CommandHandler $createHandler;';
        $lines[] = '';
        $lines[] = '    public function __construct(';
        $lines[] = '        Create' . $targetAggregate . 'CommandHandler $createHandler';
        $lines[] = '    ) {';
        $lines[] = '        $this->createHandler = $createHandler;';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function handle(' . $sourceAggregate . $eventName . ' $event): void';
        $lines[] = '    {';
        $lines[] = '        // TODO: Implement logic to react to ' . $sourceAggregate . $eventName;
        $lines[] = '        // Example: create or update related ' . $targetAggregate . ' entity';
        $lines[] = '        $command = new Create' . $targetAggregate . 'Command(';
        $lines[] = '            // Extract relevant data from event';
        $lines[] = '            $event->getId(),';
        $lines[] = '            // ... map event properties to command properties';
        $lines[] = '        );';
        $lines[] = '';
        $lines[] = '        $this->createHandler->handle($command);';
        $lines[] = '    }';
        $lines[] = '}';
        $lines[] = '';

        file_put_contents($path, implode("\n", $lines));
    }

    private function parseProperties(array $properties): array
    {
        $props = [];
        foreach ($properties as $prop) {
            $nullable = $prop['nullable'] ?? false;
            $type = $prop['type'];
            $name = '$' . lcfirst($prop['name']);
            $props[] = [
                'declaration' => ($nullable ? '?' : '') . $type . ' ' . $name,
                'name' => $prop['name'],
                'type' => $type,
                'isVO' => $prop['isVO'] ?? false,
                'validations' => $prop['validations'] ?? [],
            ];
        }
        return $props;
    }

    private function generateSimpleEntity(string $path, string $name, array $props): void
    {
        // Аналогично generateEntity, но без extends Aggregate и без status
        $propDeclarations = [];
        $propFields = [];
        $constructorParams = [];
        $assignments = [];

        foreach ($props as $prop) {
            if (strtolower($prop['name']) === 'status') continue;
            $capitalizedName = ucfirst($prop['name']);
            $propDeclarations[] = '    private ' . $prop['declaration'] . ';';
            $propFields[] = '    public function get' . $capitalizedName . '(): ' . $prop['type'] . '{ return $this->' . lcfirst($prop['name']) . '; }';
            $constructorParams[] = $prop['declaration'];
            $assignments[] = '        $this->' . lcfirst($prop['name']) . ' = $' . lcfirst($prop['name']) . ';';
        }

        $propDeclBlock = implode("\n", $propDeclarations);
        $propFieldBlock = implode("\n", $propFields);
        $assignBlock = implode("\n", $assignments);
        $constructorParamsStr = implode(', ', $constructorParams);
        $constructorPrefix = $constructorParamsStr ? ', ' : '';

        $constructorCallParams = [];
        foreach ($props as $prop) {
            if (strtolower($prop['name']) === 'status') continue;
            $constructorCallParams[] = '$' . lcfirst($prop['name']);
        }
        $constructorCallParamsStr = implode(', ', $constructorCallParams);
        $constructorCallPrefix = $constructorCallParamsStr ? ', ' : '';

        $lines = [];
        $lines[] = '<?php';
        $lines[] = 'declare(strict_types=1);';
        $lines[] = '';
        $lines[] = 'namespace Medigi\\' . $name . '\\Domain\\Entity;';
        $lines[] = '';
        $lines[] = 'use Medigi\\' . $name . '\\Domain\\VO\\ID;';
        $lines[] = 'use DateTimeImmutable;';
        $lines[] = '';
        $lines[] = 'class ' . $name;
        $lines[] = '{';
        $lines[] = '    private ID $id;';
        $lines[] = '    private DateTimeImmutable $createdAt;';
        if ($propDeclBlock) {
            $lines[] = $propDeclBlock;
        }
        $lines[] = '';
        $lines[] = '    private function __construct(ID $id' . $constructorPrefix . $constructorParamsStr . ')';
        $lines[] = '    {';
        $lines[] = '        $this->id = $id;';
        $lines[] = '        $this->createdAt = new DateTimeImmutable();';
        if ($assignBlock) {
            $lines[] = $assignBlock;
        }
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public static function create(ID $id' . $constructorPrefix . $constructorParamsStr . '): self';
        $lines[] = '    {';
        $lines[] = '        return new self($id' . $constructorCallPrefix . $constructorCallParamsStr . ');';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function getId(): ID';
        $lines[] = '    {';
        $lines[] = '        return $this->id;';
        $lines[] = '    }';
        if ($propFieldBlock) {
            $lines[] = '';
            $lines[] = $propFieldBlock;
        }
        $lines[] = '}';

        file_put_contents($path, implode("\n", $lines));
    }

    private function generateEntity(string $path, string $name, array $props, array $events): void
    {
        $propDeclarations = [];
        $propFields = [];
        $constructorParams = [];
        $assignments = [];

        $filteredProps = array_filter($props, fn($p) => strtolower($p['name']) !== 'status');

        foreach ($filteredProps as $prop) {
            $capitalizedName = ucfirst($prop['name']);
            $propDeclarations[] = '    private ' . $prop['declaration'] . ';';
            $propFields[] = '    public function get' . $capitalizedName . '(): ' . $prop['type'] . '{ return $this->' . lcfirst($prop['name']) . '; }';
            $constructorParams[] = $prop['declaration'];
            $assignments[] = '        $this->' . lcfirst($prop['name']) . ' = $' . lcfirst($prop['name']) . ';';
        }

        $propDeclBlock = implode("\n", $propDeclarations);
        $propFieldBlock = implode("\n", $propFields);
        $assignBlock = implode("\n", $assignments);
        $constructorParamsStr = implode(', ', $constructorParams);
        $constructorPrefix = $constructorParamsStr ? ', ' : '';

        $constructorCallParams = [];
        foreach ($filteredProps as $prop) {
            $constructorCallParams[] = '$' . lcfirst($prop['name']);
        }
        $constructorCallParamsStr = implode(', ', $constructorCallParams);
        $constructorCallPrefix = $constructorCallParamsStr ? ', ' : '';

        $lines = [];
        $lines[] = '<?php';
        $lines[] = 'declare(strict_types=1);';
        $lines[] = '';
        $lines[] = 'namespace Medigi\\' . $name . '\\Domain\\Entity;';
        $lines[] = '';
        $lines[] = 'use Medigi\\' . $name . '\\Domain\\Entity\\Aggregate;';
        $lines[] = 'use Medigi\\' . $name . '\\Domain\\Entity\\EventTrait;';
        $lines[] = 'use Medigi\\' . $name . '\\Domain\\VO\\ID;';
        $lines[] = 'use Medigi\\' . $name . '\\Domain\\VO\\Status;';

        foreach ($events as $event) {
            $lines[] = 'use Medigi\\' . $name . '\\Domain\\Event\\' . $name . '\\' . $name . $event . ';';
        }

        $lines[] = 'use Medigi\\' . $name . '\\Domain\\Exception\\' . $name . '\\' . $name . 'EmptyNameException;';
        $lines[] = 'use DateTimeImmutable;';
        $lines[] = '';
        $lines[] = 'class ' . $name . ' extends Aggregate';
        $lines[] = '{';
        $lines[] = '    use EventTrait;';
        $lines[] = '';
        $lines[] = '    private ID $id;';
        $lines[] = '    private Status $status;';
        $lines[] = '    private DateTimeImmutable $createdAt;';
        if ($propDeclBlock) {
            $lines[] = $propDeclBlock;
        }
        $lines[] = '';
        $lines[] = '    private function __construct(ID $id' . $constructorPrefix . $constructorParamsStr . ')';
        $lines[] = '    {';
        $lines[] = '        $this->id = $id;';
        $lines[] = '        $this->status = Status::Active();';
        $lines[] = '        $this->createdAt = new DateTimeImmutable();';
        if ($assignBlock) {
            $lines[] = $assignBlock;
        }
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public static function create(ID $id' . $constructorPrefix . $constructorParamsStr . '): self';
        $lines[] = '    {';
        $lines[] = '        return new self($id' . $constructorCallPrefix . $constructorCallParamsStr . ');';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function getId(): ID';
        $lines[] = '    {';
        $lines[] = '        return $this->id;';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function getStatus(): Status';
        $lines[] = '    {';
        $lines[] = '        return $this->status;';
        $lines[] = '    }';
        if ($propFieldBlock) {
            $lines[] = '';
            $lines[] = $propFieldBlock;
        }
        $lines[] = '';
        $lines[] = '    public function rename(string $newName): void';
        $lines[] = '    {';
        $lines[] = '        $oldName = $this->name ?? \'\';';
        $lines[] = '        $this->name = $newName;';
        $lines[] = '        $this->recordEvent(new ' . $name . 'Renamed($this->id, $oldName, $newName, new DateTimeImmutable()));';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function archive(): void';
        $lines[] = '    {';
        $lines[] = '        $this->status = Status::Archived();';
        $lines[] = '        $this->recordEvent(new ' . $name . 'Archived($this->id, new DateTimeImmutable()));';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function delete(): void';
        $lines[] = '    {';
        $lines[] = '        $this->status = Status::Deleted();';
        $lines[] = '        $this->recordEvent(new ' . $name . 'Deleted($this->id, new DateTimeImmutable()));';
        $lines[] = '    }';
        $lines[] = '}';

        file_put_contents($path, implode("\n", $lines));
    }

    private function generateVO(string $path, string $namespace, string $voName): void
    {
        $lines = [];
        $lines[] = '<?php';
        $lines[] = 'declare(strict_types=1);';
        $lines[] = '';
        $lines[] = 'namespace Medigi\\' . $namespace . '\\Domain\\VO;';
        $lines[] = '';
        $lines[] = 'class ' . $voName;
        $lines[] = '{';
        $lines[] = '    private string $value;';
        $lines[] = '';
        $lines[] = '    private function __construct(string $value)';
        $lines[] = '    {';
        $lines[] = '        $this->value = $value;';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public static function fromString(string $value): self';
        $lines[] = '    {';
        $lines[] = '        return new self($value);';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function toString(): string';
        $lines[] = '    {';
        $lines[] = '        return $this->value;';
        $lines[] = '    }';
        $lines[] = '';
        $lines[] = '    public function equals(self $other): bool';
        $lines[] = '    {';
        $lines[] = '        return $this->value === $other->value;';
        $lines[] = '    }';
        $lines[] = '}';

        file_put_contents($path, implode("\n", $lines));
    }

    private function createDirectory(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function generateFromTemplate(string $targetPath, string $templatePath, array $replacements): void
    {
        if (!file_exists($targetPath)) {
            if (!file_exists($templatePath)) {
                // Skip missing templates instead of crashing
                return;
            }
            $templateContent = file_get_contents($templatePath);
            $content = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $templateContent
            );
            file_put_contents($targetPath, $content);
        }
    }

    private function generateComposerJson(string $basePath, string $appName, array $aggregates): void
    {
        // Генерируем autoload для всех агрегатов
        $autoload = [];
        foreach ($aggregates as $aggregate) {
            $name = $aggregate['name'];
            $autoload["Medigi\\$name\\"] = "src/$name/";
        }

        $autoloadJson = json_encode($autoload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $autoloadDev = json_encode(["Medigi\\Tests\\" => "tests/"], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $composerJson = <<<JSON
{
    "name": "medigi/$appName",
    "description": "$appName bounded contexts",
    "type": "library",
    "license": "proprietary",
    "minimum-stability": "dev",
    "prefer-stable": true,
    "autoload": $autoloadJson,
    "autoload-dev": $autoloadDev,
    "require": {
        "php": "^8.0"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "mockery/mockery": "^1.6"
    }
}
JSON;
        file_put_contents("$basePath/composer.json", $composerJson);
    }
}