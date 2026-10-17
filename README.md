# independenceplumbingandbath: папка /local сайта на 1С-Битрикс

Корень репозитория = папка `/local` на сайте. Здесь только свой код: шаблон, компоненты, `php_interface`. Ядро Битрикса (`/bitrix`) и `/upload` в git не попадают.

```
templates/pipewise/        шаблон сайта PipeWise
  header.php, footer.php
  template_styles.css      подключается автоматически
  description.php
  include/                 редактируемые блоки (topbar, подвал)
  components/bitrix/       свои шаблоны компонентов (menu, breadcrumb, ...)
docs/site-root/            файлы, которые нужно положить в КОРЕНЬ сайта (меню)
```

## Первая установка на сервере

Путь к сайту смотрите в FASTPANEL, ниже условный `$SITE`.

```bash
cd $SITE
ls local            # если папки нет, ничего страшного
mkdir -p local && cd local
git init -b main
git remote add origin git@github.com:doppler324/independenceplumbingandbath.git
git pull origin main
```

Нужен deploy-ключ: на сервере `ssh-keygen -t ed25519`, публичную часть добавить в GitHub (Settings -> Deploy keys, только чтение).

Права: файлы должны принадлежать пользователю сайта (того же, что и остальной Битрикс), иначе веб-сервер не прочитает шаблон.

## Подключение в Битриксе

1. Настройки -> Настройки продукта -> Сайты -> Шаблоны сайтов -> Добавить, выбрать `pipewise`, привязать к сайту.
2. Скопировать `docs/site-root/.top.menu.php` и `.bottom.menu.php` в корень сайта (или создать такие меню в админке).
3. Включить для сайта язык English и проверить `LANGUAGE_ID`.

## Обновление

На сервере: `cd $SITE/local && git pull`.
После обновления сбросить кеш: Настройки -> Производительность -> Автокеширование -> Очистить файлы кеша.

## Правила работы

- Правим код только в репозитории, не в админке на сервере (иначе `git pull` упрётся в конфликт).
- Папку шаблона не переименовываем, она привязана к сайту по имени.
