#JaraCMS MVC API

The model view controller based rest api for JaraCMS

## Documentations

### CLI

#### Create New MVC files

```bash
php jara
```

```bash
mvc name
```

#### Backup Database

```bash
php jara
```

```bash
backup
```

#### Clear and Exit

```bash
php jara
```

```bash
clear
```

```bash
exit
```

### Routes
Routes can be added in `/Routes/Routes.php` file.

#### Add Route
Params: $method, $path, $view

$method can be all valid http request types including, get, post, delete, update.
$view can be an array of class and method or function

```php
$app->router->any('get', '/', [new NameView(), 'nameOfMethod']);
```

### Settings 
Settings can be managed with the static methods of Setting class.

#### Get
```php
Setting::get('name_of_key', 'default optional value if not fount');
```
#### Set
```php
Setting::get('name_of_key', 'value to store');
```
#### Update
```php
Setting::update('name_of_key', 'new value');
```
#### Remove
```php
Setting::remove('name_of_key');
```


Docs will be added here.

## TODOs

[-] Create Rest API
    [-] Admin API
    [-] Client API
[-] User Interface 
    [-] Client UI
    [-] Admin UI
    [-] Mobile App UI
[-] Build Native Apps
    [-] Android App [Frontend Only]
    [-] IOS App [Frontend Only]
    [-] Windows App [Admin Panel Only]
    [-] Linux App [Admin Panel Only]
    [-] MacOS App [Admin Panel Only]

### Time management

- Scalable Database: 6-8 hours 
- Essential Functions: 8 - 10 hours
- Build Secure Authentication: 5 hours
- Basic API: 8 hours

- Admin Frontend: 30 hours +
- User Frontend: 30 hours +