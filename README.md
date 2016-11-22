# Installation

```PHP
class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
    	...
        $this->addBehavior('Gps.Gps');
        ...
    }
}
```