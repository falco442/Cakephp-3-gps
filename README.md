# Installation

Add in `config/bootstrap.php` the line

```PHP
Plugin::load('Gps');
```

and in `src/Model/Table/YourModelTable.php` the lines

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