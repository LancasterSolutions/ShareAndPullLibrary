# ShapLibrary

To create a new ShapLibrary and access it's methods:

```php
$apiKey = 'Your api key for the required list"

$shapLibrary = new ShapLibrary($apiKey);
$shapLibrary->details();
```

You can call the implemented methods:

# getListDetails
Return the list of dataTypes for each element inside the list
```php
//@return array $listElements
$shapLibrary->details(): array;
```

# pushMessages
Push messages to SHAP. It will enqueue messages into the shap validator interface. After
validation SHAP will grant credits.
```php
//@return int $totalPushed
$shapLibrary->push(array $messages): int ;
```

# pullMessages
Return the new messages from the list. It works based on a timestamp. It has a limit
configured by server on the max amount of messages by pull. between 200 - 1000.

You will have to give a timestamp that corresponds the "from" in the incremental query.

```php
//@return array $messages
$shapLibrary->pull(int $timestamp): array;
```
