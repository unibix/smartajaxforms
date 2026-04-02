# SmartAjaxForms
Умные и удобные формы для любого сайта и CMS.

## Подключение и настройка

1. Для подключения необходимо в html-файл необходимой страницы перед закрывающим тегом body поместить следующий код:

~~~html
<script  src="/smartajaxform.js"></script>
~~~

2. для формы задать (или добавить) css class smartajaxform, пример class="smartajaxform" или  class="oldclass smartajaxform" - если у формы есть другие классы
3. из обязательных полей телефон - name=ajaxx_phone
4. добавьте скрытое поле куда система запишет URL с которого заполнили форму: name=url type=hidden с пустым значением. Пример: 
~~~html
<input type="hidden" name="url">
~~~
5. добавьте скрытое поле где будет передавать имя заполненной формы: name=formname type=hidden value="Заказать звонок". Пример: 
~~~html
<input name=formname type=hidden value="Заказать звонок">
~~~ 
6. добавьте блок с сообщением об успешной отправке, изначально оно должно быть display:none. и должно идти сразу после тега form с классом "messageAjaxForm". Пример: 
~~~html
<div class="messageAjaxForm" style="display:none;background: #9de59d;padding:2rem;">Сообщение успешно отправлено! Сейчас вам перезвонит наш менеджер!</div>
~~~ 
7. в файле smartajaxform.php указать куда вы хотите принимать лиды: 
- если в csv-файл, то укажите CSV_FILENAME, например "leadsfile.csv"
- если в телеграмм, то укажите токен TG_TOKEN и айди чата
- если в макс, то укажите токен MAX_TOKEN и айди чата 
- если на емейл, то укажите EMAIL
- если не указать ни одного из доступных способов, то ошибки не будет.

Если возникли сложности с установкой - смотрите example.html

## Из необязательного
1. Если хотите правильно собирать цели в яндекс метрике, то укажите ее айди в smartajaxform.js

2. дополнительные поля добавляем по следующей схеме: name="ajax_{название поля}" и поле его описывающее: name="ajax_name_{название поля}" value="Комментарий"
пример: name="ajax_telegram" и name="ajax_name_telegram" value="Телеграм" 

3. чтобы подключить яндекс капчу скрытую нужно в форме добавить поле 

~~~html
<div id="captcha-container" style="display: none;"></div>
<input type="hidden" name="smartcaptcha_token" id="smartcaptcha_token">
~~~


## Запланировано:

1. вставка формы в любое место простым кодом

~~~html
<script  src="/ajaxform.js?insertform=1&formname=withPhone"></script>
~~~

2. подсказки во время заполнения формы

3. автоматическая проверка правильности установки форм, для этого нужно к урлу добавить ?checksmart=1
