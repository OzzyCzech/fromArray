<?php declare(strict_types=1);

use DataLoader\FromArray;
use DataLoader\Type;
use DataLoader\Types;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';

class Item
{
    public function __construct(
        public array $data = [],
    ) {}
}

class ItemCollection
{
    use FromArray;

    #[Type(name: Types::ArrayOfObjects, class: Item::class)]
    public array $items = [];
}

test('ArrayOfObjects type', function () {
    $collection = ItemCollection::fromArray([
        'items' => [
            ['name' => 'first'],
            ['name' => 'second'],
        ],
    ]);

    Assert::count(2, $collection->items);
    Assert::type(Item::class, $collection->items[0]);
    Assert::type(Item::class, $collection->items[1]);
    Assert::same(['name' => 'first'], $collection->items[0]->data);
    Assert::same(['name' => 'second'], $collection->items[1]->data);
});

test('ArrayOfObjects with empty array', function () {
    $collection = ItemCollection::fromArray(['items' => []]);
    Assert::count(0, $collection->items);
});
