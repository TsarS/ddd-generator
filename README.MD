# Medigi DDD Generator

CLI-инструмент для генерации DDD-структуры: bounded contexts, aggregates, entities, VOs, events, commands, queries, listeners и тестов.

## Установка

```bash
composer global require medigi/ddd-generator
```

Или клонируйте репозиторий и установите зависимости:

```bash
git clone https://github.com/medigi/ddd-generator.git
cd ddd-generator
composer install
```

## Использование

### Базовое использование

```bash
php ddd create <appName> --config=/path/to/ddd.json --events=/path/to/events.json
```

### Пример

```bash
php ddd create medigi --config=./ddd.json --events=./events.json
```

## Конфигурационные файлы

### ddd.json - Основная конфигурация

```json
{
  "appName": "medigi",
  "aggregates": [
    {
      "name": "LabTest",
      "isAggregate": true,
      "properties": [
        {"name": "name", "type": "string", "validations": ["notEmpty"]},
        {"name": "description", "type": "string", "nullable": true},
        {"name": "category", "type": "Category", "isVO": true, "nullable": true}
      ],
      "events": ["Created", "Renamed", "Deleted", "Archived", "Reinstated"],
      "commands": ["Create", "Archive", "Delete", "Reinstate", "Rename"],
      "queries": ["GetAll", "GetById", "GetByName", "Unique"]
    }
  ]
}
```

#### Поля агрегата:

| Поле | Тип | Описание |
|------|-----|----------|
| `name` | string | Имя агрегата/сущности |
| `isAggregate` | bool | Если `true` - генерирует Aggregate с status, archive, delete. Если `false` - простая Entity |
| `properties` | array | Список свойств сущности |
| `properties[].name` | string | Имя свойства |
| `properties[].type` | string | PHP тип (string, int, etc.) или имя VO |
| `properties[].nullable` | bool | Может ли быть null |
| `properties[].isVO` | bool | Является ли тип Value Object |
| `properties[].validations` | array | Список валидаций ( пока не реализовано) |
| `events` | array | Список доменных событий |
| `commands` | array | Список команд (Create, Archive, etc.) |
| `queries` | array | Список запросов (GetAll, GetById, etc.) |

### events.json - Межагрегатные связи

```json
{
  "subscribers": [
    {
      "source": "LabTest",
      "event": "Created",
      "target": "ICD10",
      "description": "When LabTest is created, create related ICD10 entry"
    }
  ]
}
```

#### Поля subscriber:

| Поле | Тип | Описание |
|------|-----|----------|
| `source` | string | Имя агрегата-источника события |
| `event` | string | Имя события (Created, Archived, etc.) |
| `target` | string | Имя агрегата-цели, где будет создан адаптер |
| `description` | string | Описание связи (опционально) |

## Структура генерации

Для каждого агрегата создаётся:

```
src/<AggregateName>/
├── Domain/
│   ├── Entity/
│   │   ├── <AggregateName>.php          # Основная сущность
│   │   ├── Aggregate.php                 # Базовый класс Aggregate (если isAggregate=true)
│   │   └── EventTrait.php                # Trait для событий
│   ├── VO/
│   │   ├── ID.php                        # Value Object ID
│   │   ├── Status.php                     # Status VO
│   │   └── <OtherVO>.php                  # Пользовательские VOs
│   ├── Event/<AggregateName>/
│   │   ├── <AggregateName>Created.php
│   │   ├── <AggregateName>Archived.php
│   │   └── ...
│   ├── Exception/<AggregateName>/
│   │   └── <AggregateName>EmptyNameException.php
│   └── Repository/
│       └── <AggregateName>RepositoryInterface.php
├── Application/<AggregateName>/
│   ├── Command/
│   │   ├── Create/
│   │   │   ├── Create<AggregateName>Command.php
│   │   │   └── Create<AggregateName>CommandHandler.php
│   │   ├── Archive/
│   │   └── ...
│   ├── Query/
│   │   ├── GetAll/
│   │   ├── GetById/
│   │   └── ...
│   ├── Listener/
│   │   ├── <AggregateName>CreatedListener.php
│   │   └── ...
│   └── Adapter/                          # Межагрегатные адаптеры (из events.json)
│       └── <Event><Source>To<Target>Subscriber.php
└── Infrastructure/
    ├── API/
    └── Persistance/
```

## Примеры конфигов

### Простой агрегат

```json
{
  "name": "Patient",
  "isAggregate": true,
  "properties": [
    {"name": "firstName", "type": "string"},
    {"name": "lastName", "type": "string"},
    {"name": "email", "type": "string"}
  ],
  "events": ["Created", "Updated", "Deleted"],
  "commands": ["Create", "Update", "Delete"],
  "queries": ["GetById", "GetAll"]
}
```

### Неагрегатная сущность с Value Object

```json
{
  "name": "Address",
  "isAggregate": false,
  "properties": [
    {"name": "street", "type": "string"},
    {"name": "city", "type": "City", "isVO": true},
    {"name": "zipCode", "type": "string"}
  ],
  "events": ["Created"],
  "commands": ["Create"],
  "queries": ["GetAll"]
}
```

В данном примере `City` будет сгенерирован как Value Object в `Domain/VO/City.php`, а не как отдельная сущность.

### С событиями и межагрегатными связями

**ddd.json:**
```json
{
  "appName": "clinic",
  "aggregates": [
    {
      "name": "Appointment",
      "isAggregate": true,
      "properties": [
        {"name": "patientId", "type": "string"},
        {"name": "doctorId", "type": "string"},
        {"name": "scheduledAt", "type": "string"}
      ],
      "events": ["Created", "Cancelled", "Completed"],
      "commands": ["Create", "Cancel", "Complete"],
      "queries": ["GetById", "GetAll", "GetByPatient"]
    },
    {
      "name": "Notification",
      "isAggregate": true,
      "properties": [
        {"name": "message", "type": "string"},
        {"name": "recipient", "type": "string"}
      ],
      "events": ["Sent"],
      "commands": ["Send"],
      "queries": ["GetByRecipient"]
    }
  ]
}
```

**events.json:**
```json
{
  "subscribers": [
    {
      "source": "Appointment",
      "event": "Created",
      "target": "Notification",
      "description": "Send notification when appointment is created"
    },
    {
      "source": "Appointment",
      "event": "Cancelled",
      "target": "Notification",
      "description": "Notify patient about cancellation"
    }
  ]
}
```

## Генерируемые файлы

### Entity (Aggregate)

```php
class LabTest extends Aggregate
{
    use EventTrait;

    private ID $id;
    private Status $status;
    private DateTimeImmutable $createdAt;
    private string $name;

    // Constructor, factory method, getters...

    public function archive(): void { ... }
    public function delete(): void { ... }
    public function rename(string $newName): void { ... }
}
```

### Event Subscriber (Adapter)

```php
class CreatedLabTestToICD10Subscriber
{
    public function handle(LabTestCreated $event): void
    {
        // TODO: Implement logic to react to LabTestCreated
        // Create or update related ICD10 entity
    }
}
```

## Тесты

Для каждого агрегата генерируются базовые тесты в `tests/<AggregateName>/`:

- `Domain/Entity/<AggregateName>Test.php`
- `Domain/Event/<AggregateName><Event>Test.php`
- `Application/Command/<Command>/<Command><AggregateName>CommandHandlerTest.php`
- `Application/Query/<Query>/<Query><AggregateName>QueryHandlerTest.php`
- `Domain/Repository/<AggregateName>RepositoryTest.php`

## Разработка

### Структура проекта DDD Generator

```
ddd-generator/
├── src/
│   ├── DDDGenerator.php          # Основной генератор
│   └── Command/
│       └── CreateAppCommand.php  # CLI команда
├── templates/                     # Шаблоны для генерации
│   ├── *.php.template
│   ├── test/
│   │   └── *.php.template
│   └── config/
│       ├── ddd.json
│       └── events.json
├── ddd.php                       # Точка входа
└── composer.json
```

### Запуск тестов

```bash
composer test
# или
./vendor/bin/phpunit
```

## Лицензия

MIT