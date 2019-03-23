# Larafast
Build your request Eloquent via url parameters
...

### Installation
Install the package
```sh
composer require sanabuk/larafast
```

Publish config file larafast.php
```sh
php artisan vendor:publish sanabuk\larafast\LarafastServiceProvider
```

### Principes
Edit config/larafast.php
```php
return [
    'models' => [
        'your_model_name' => 'namespace model',
        ...
    ],
    'relations' => [
        ...,
        ...
    ]
];
```
       
Write your url with 3 parameters:
- **model** : the requested model
- **conditions** : applied on requested model

> **Available conditions:**
> - equals
> - like
> - max
> - min
- **output** : output format

> **example** : I want to recover `animal` with a `name` containing the letters `ca` and an `id` max of `20`. I want recover animal's name, his class sort by their id desc
> ```sh
> HTTPS GET
> https://my_project.com/animal?model=animal&conditions=like=name:ca,max=id:20&output=id,name,class,sort=-id
> ```


