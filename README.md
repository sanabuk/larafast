# Larafast
Build your request Eloquent via url parameters

### Principes

In ModelController@index :
- Instanciate sanabuk\larafast\Larafast
- Call function getDatas(Request, Model) that returns Eloquent\Builder
```php
use sanabuk\larafast\Larafast

public function index(Request $request)
{
    $larafast = new Larafast();
    $datas   = $larafast->getDatas($request, new Model());
    return new JsonResponse($datas->paginate(15)->appends($request->input()), 200);
}
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

### Installation
```sh
composer require sanabuk/larafast
```
