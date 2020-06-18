## Area Chart

The value method of an area chart requires to return an integer. The method's first parameter is a query builder that can be used to calculate the value like shown in the example:

```php
public function value($query)
{
    return $this->count($query);
}
```

Possible methods are:

```php
return $this->count($query);
return $this->average($query, 'price');
return $this->min($query, 'price');
return $this->max($query, 'price');
return $this->sum($query, 'price');
```

In addition, the total value must be calculated for an area chart. This value is shown at the bottom left of the card. This value is calculated in the result method like this:

```php
use Illuminate\Support\Collection;

public function result(Collection $values)
{
    // Sum:
    return $values->sum();
    // Average:
    return round($values->average(), 2);
}
```
