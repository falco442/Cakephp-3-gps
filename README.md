# Introduction

This plugin enable the search for some entity, given its coordinates.

Requirements:
* CakePHP 3.x


# Installation

## Table entry

Add the fields (choose your names, or use the default 'latitude' and 'longitude'). I suggest to use the `FLOAT(10,6)`. Note that, if you change the field names, you must provide them in the plugin configuration.

## Setting up the code

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

You can also provide a configuration with the plugin, extending this lines:

```PHP
class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
    	...
        $this->addBehavior('Gps.Gps',[
        	'radius'=>6896000000.231,
        	'fields'=>[
        		'latitude'=>'lat',
        		'longitude'=>'lon'
        	]
        ]);
        ...
    }
}
```

For example, in this configuration, we're informing the plugin that the fields in the table will be of names 'lat' for latitude, and 'lon' for longitude. The Earth radius will be of `6896000000.231` km.

## Usage

in `src/Controller/YourModelController`, for example, in a `nearBy` action, you could have

```PHP
public function nearBy(){
	$yourmodel = $this->YourModel->find('near',$this->request->query);
	$this->set(compact('yourmodel'));
	$this->set('_serialize',['yourmodel']);
}
```

and so the conditions will be the ones urlencoded:

```
http://yourdomain.com/youmodel/near-by.json?latitude=43.2&longitude=24.356&radius=20000
```

With this url we're telling ours API software to take the objects in a square with center in `43.2`,`24.35` and side of `20000` meters

