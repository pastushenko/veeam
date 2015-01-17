#Тестовое задание для Veeam
Задание [тут](task.txt)

##О проекте:
Написан на [Symfony2](http://symfony.com/). В [Symfony2](http://symfony.com/) из коробки используется шаблонизатор [Twig](http://twig.sensiolabs.org/), но я не использовал разные генераторы форм и т.д. и т.п.
От фреймфорка использовано только основа, [DI](http://symfony.com/doc/current/components/dependency_injection/introduction.html), [Doctrine2](http://www.doctrine-project.org/), роуты, пару базовых объектов.
Большинство функциональности написано в обычном ооп стиле.
Код лежит в /src/Vacancy - в 3-х бандлах. В директории каждого бандла еслть раздел "Resources", там можете найти все конфиги, роуты, схемы.
Стороние библиотеки - [jQuery](https://jquery.org/), [Bootstrap3](http://getbootstrap.com/).

##Руководство по установке:
Для работы приложения необходимы php 5.4 и драйвер php-mysql.
Также необходимо установить mysql.
Необходимо создать пользователя и бд (тип хранилища InnoDB).
* Сперва склонируйте к себе проект: <code>git clone https://github.com/pastushenko/veeam.git</code>
* Выполните composer: <code>php composer.phar install</code>
* Укажите настроки бд (остальные пропустите).
* Выполните команду генерирующею схему бд: <code>./app/console doctrine:schema:create</code>
* Выполните команду генерирующею тестовые данные в бд: <code>./app/console vacancies:initial-fill-db</code>
* Для запуска веб сервера используйте комманду: <code>./app/console server:run</code>

##Известные недостатки:
* В api, выводятся в параметре message все отловленные исключения.
Если капнуть глубже, нужно создать интерфейс, к примеру "UserViewableExceptions" и показывать только те исключения, которые наследуют этот интерфейс.
* В случе отрицательного ответа от апи - не предпринимается некаких действий, просто выводится alert('error message');
* Необходим рефакторинг в javascript.