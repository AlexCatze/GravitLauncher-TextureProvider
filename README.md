# GravitLauncher-TextureProvider (JSON)

![PHP 5.6.0](https://img.shields.io/badge/PHP-5.6.0-blue)
![Gravit Launcher](https://img.shields.io/badge/Gravit%20Launcher-5.2.0-brightgreen)

## Выдача данных для скинов и плащей. GravitLauncher 5.2.0+

✔ Выдача данных для default (classic) и обнаружение slim скинов.

✔ Работает с любыми общепринятыми размерами скинов и плащей

✖ Выдача скинов из папки. (Не реализовано)

✖ Выдача плащей из папки. (Не реализовано)

<p align="center">
    <img src="https://i.imgur.com/q0nkKNj.png" alt="demo" width="642">
</p>

<h1 align="center">
<br>
Требования
</h1>

- PHP 5.6+
- GravitLauncher 5.2.0+ [#Новый authlib](https://mirror.gravit.pro/compat/authlib/2/LauncherAuthlib2-5.2.0.jar) [#Правки](https://github.com/GravitLauncher/Launcher/compare/fecc14010d30...5d0ccdbde3b9)
- Расширение GD `php-gd`. Пример для PHP 7.4: `sudo apt-get install php7.4-gd`

<h1 align="center">
<br>
НАСТРОЙКА
</h1>

- **Настройка пути к скинам и плащам**
```php
    const SKIN_PATH = "./minecraft-auth/skins/"; // Сюда вписать путь до skins/
    const CLOAK_PATH = "./minecraft-auth/cloaks/"; // Сюда вписать путь до cloaks/
```
`../ - одна директория вверх`
`minecraft-auth папка указана для примера`

- **Настройка отдаваемых ссылок**
```php
    const SKIN_URL = "https://example.com/minecraft-auth/skins/%login%.png";
    const CLOAK_URL = "https://example.com/minecraft-auth/cloaks/%login%.png";
```
Можете спокойно перенести ссылки из уже настроеных в конфиге лаунчсервера, заменив только заполнитель на %login%

<h1 align="center">
<br>
Настройка textureProvider в LaunchServer.json
</h1>

- **На имени пользователя**
```json
"textureProvider":{
      "url":"https://example.com/TextureProvider.php?login=%username%",
      "type":"json"
   }
```

- **На UUID**
```json
"textureProvider":{
      "url":"https://example.com/TextureProvider.php?login=%uuid%",
      "type":"json"
   }
```

<h1 align="center">
<br>
Примеры ответа в браузере
</h1>

- **При наличии скина slim и плаща**
```json
{
    "skin": {
        "url": "https://example.com/minecraft-auth/skins/slim.png",
        "digest": "MDk0NTFjMTZjM2EyNzBlZGNhYTUwNzMyYjJjNzNhMzk=",
        "metadata": {
            "model": "slim"
        }
    },
    "cloak": {
        "url": "https://example.com/minecraft-auth/cloaks/slim.png",
        "digest": "ZGM5NGZkNzgyYzBjZmUyNzQ5YTgyNDJhOWI0NDkzNTA="
    }
}
```

- **При наличии только default скина**
```json
{
    "skin": {
        "url": "http://127.0.0.1/minecraft-auth/skins/default.png",
        "digest": "YjQ2ZTM4ODljNzBlMGJiOWUyYmExYzdkNGM2ZTI5Zjc="
    }
}
```
