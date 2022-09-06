# Тестовое задание для PHP-разработчика.

1.  Используя фреймворк Laravel реализовать RESTful api.

2.  Реализовать сущности
    -   Товары
    -   Категории
    -   Товар-Категория

3.  Реализовать выдачу данных в формате json по RESTful

-   Создание Товаров (у каждого товара может быть от 2х до 10 категорий)

-   Редактирование Товаров

-   Удаление товаров (товар помечается как удаленный)

-   Создание категорий

-   Удаление категорий (вернуть ошибку если категория прикреплена к товару)

-   Получение списка товаров

     1.  Имя / по совпадению с именем

     2.  id категории

     3.  Название категории / по совпадению с категорией

     4.  Цена от - до

     5.  Опубликованные да / нет

     6.  Не удаленные

Результат представить ссылкой на репозиторий.
Важно, в репозиторий залить пустой каркас приложения, а затем с
внесенными изменениями, чтобы можно было проследить diff.

# Что доделать
- Вынести работу с сущностями в сервисы
- Рефакторинг
- Возможно стоит создать Form классы для сущностей