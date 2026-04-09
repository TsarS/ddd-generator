# PHP DDD Generator

CLI tool to generate DDD structure: bounded contexts, aggregates, entities, VOs, events, commands, queries, listeners, and tests.

## Installation

```bash
composer global require tsars/ddd-generator
```

Or clone the repository and install dependencies:

```bash
git clone https://github.com/TsarS/ddd-generator.git
cd ddd-generator
composer install
```

## Usage

### Basic usage

```bash
php ddd create <appName> --config=/path/to/ddd.json --events=/path/to/events.json
```

### Example

```bash
php ddd create myproject --config=./ddd.json --events=./events.json
```

## Configuration Files

### ddd.json - Main Configuration

```json
{
  "appName": "myproject",
  "aggregates": [
    {
      "name": "Product",
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

#### Aggregate Fields:

| Field | Type | Description |
|------|------|-------------|
| `name` | string | Aggregate/entity name |
| `isAggregate` | bool | If `true` - generates Aggregate with status, archive, delete. If `false` - simple Entity |
| `properties` | array | List of entity properties |
| `properties[].name` | string | Property name |
| `properties[].type` | string | PHP type (string, int, etc.) or VO name |
| `properties[].nullable` | bool | Can be null |
| `properties[].isVO` | bool | Is this type a Value Object |
| `properties[].validations` | array | List of validations (not yet implemented) |
| `events` | array | List of domain events |
| `commands` | array | List of commands (Create, Archive, etc.) |
| `queries` | array | List of queries (GetAll, GetById, etc.) |

### events.json - Inter-Aggregate Links

```json
{
  "subscribers": [
    {
      "source": "Product",
      "event": "Created",
      "target": "Category",
      "description": "When Product is created, update Category stats"
    }
  ]
}
```

#### Subscriber Fields:

| Field | Type | Description |
|------|------|-------------|
| `source` | string | Source aggregate name |
| `event` | string | Event name (Created, Archived, etc.) |
| `target` | string | Target aggregate name where adapter will be created |
| `description` | string | Link description (optional) |

## Generated Structure

For each aggregate:

```
src/<AggregateName>/
├── Domain/
│   ├── Entity/
│   │   ├── <AggregateName>.php          # Main entity
│   │   ├── Aggregate.php                 # Base Aggregate class (if isAggregate=true)
│   │   └── EventTrait.php                # Event trait
│   ├── VO/
│   │   ├── ID.php                        # Value Object ID
│   │   ├── Status.php                    # Status VO
│   │   └── <OtherVO>.php                 # Custom VOs
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
│   └── Adapter/                          # Inter-aggregate adapters (from events.json)
│       └── <Event><Source>To<Target>Subscriber.php
└── Infrastructure/
    ├── API/
    └── Persistance/
```

## Configuration Examples

### Simple Aggregate

```json
{
  "name": "User",
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

### Non-Aggregate Entity with Value Objects

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

In this example, `City` will be generated as a Value Object in `Domain/VO/City.php`, not as a separate entity.

### With Events and Inter-Aggregate Links

**ddd.json:**
```json
{
  "appName": "shop",
  "aggregates": [
    {
      "name": "Order",
      "isAggregate": true,
      "properties": [
        {"name": "customerId", "type": "string"},
        {"name": "totalAmount", "type": "Money", "isVO": true}
      ],
      "events": ["Created", "Cancelled", "Completed"],
      "commands": ["Create", "Cancel", "Complete"],
      "queries": ["GetById", "GetAll", "GetByCustomer"]
    },
    {
      "name": "Invoice",
      "isAggregate": true,
      "properties": [
        {"name": "amount", "type": "Money", "isVO": true},
        {"name": "status", "type": "InvoiceStatus", "isVO": true}
      ],
      "events": ["Generated", "Paid"],
      "commands": ["Generate", "MarkPaid"],
      "queries": ["GetByOrder"]
    }
  ]
}
```

**events.json:**
```json
{
  "subscribers": [
    {
      "source": "Order",
      "event": "Completed",
      "target": "Invoice",
      "description": "Generate invoice when order is completed"
    },
    {
      "source": "Order",
      "event": "Cancelled",
      "target": "Invoice",
      "description": "Cancel invoice when order is cancelled"
    }
  ]
}
```

## Generated Files

### Entity (Aggregate)

```php
class Product extends Aggregate
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
class CreatedProductToCategorySubscriber
{
    public function handle(ProductCreated $event): void
    {
        // TODO: Implement logic to react to ProductCreated
        // Create or update related Category entity
    }
}
```

## Tests

For each aggregate, basic tests are generated in `tests/<AggregateName>/`:

- `Domain/Entity/<AggregateName>Test.php`
- `Domain/Event/<AggregateName><Event>Test.php`
- `Application/Command/<Command>/<Command><AggregateName>CommandHandlerTest.php`
- `Application/Query/<Query>/<Query><AggregateName>QueryHandlerTest.php`
- `Domain/Repository/<AggregateName>RepositoryTest.php`

## Development

### Project Structure

```
ddd-generator/
├── src/
│   ├── DDDGenerator.php          # Main generator
│   └── Command/
│       └── CreateAppCommand.php  # CLI command
├── templates/                    # Templates for generation
│   ├── *.php.template
│   ├── test/
│   │   └── *.php.template
│   └── config/
│       ├── ddd.json
│       └── events.json
├── ddd.php                      # Entry point
└── composer.json
```

### Run Tests

```bash
composer test
# or
./vendor/bin/phpunit
```

## License

MIT