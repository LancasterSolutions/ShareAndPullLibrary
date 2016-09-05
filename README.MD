# ShapLibrary

To create a new ShapLibrary and access it's methods:

```php
$apiKey = 'Your api key for the required list"

$shapLibrary = new ShapLibrary($apiKey);
$shapLibrary->details();
```

You can call the implemented methods:

# Details
Return the list of dataTypes for the given list. For example:

[
    "listName" => 'Blacklisted',
    "listId" => 1,
    "ListDetails" => [
      "email"
    ]
]

```php
//@return array $listElements
$shapLibrary->details();
```

# pushMessages
Push messages to SHAP. It will enqueue messages into the shap validator interface. After
validation SHAP will grant credits. This method returns the amount of messages pushed to SHAP

the content of the messages array have to match to the list Datatypes. Use the details call to get information
from the list. 

example:

$messages = [["email" => "email@email.com"], ["email" => "email2@email.com], [...]]

```php
//@return int $totalPushed
$shapLibrary->push(array $messages);
```

# pullMessages
Return the new messages from the list. It works based on a timestamp. It has a limit
configured by server on the max amount of messages by pull. between 200 - 1000.

You will have to give a timestamp that corresponds the "from" in the incremental query.
To synchronize a entire database:

GET /pull/1 ... (grab the latest timestamp) //Ordered always by timestamp so it matches the last element
GET /pull/12843434 ...
GET /pull/xxx until you have 0 messages as an answer

then you know that you are updated. Next time you do a get use the latest timestamp so you only collect the new messages and don't consume more credits than required. 

```php
//@return array $messages
$shapLibrary->pull(int $timestamp);
```
