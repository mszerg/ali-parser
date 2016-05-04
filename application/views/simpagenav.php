<?php
/**
 * @author Artem aka Moskitos <arty-komarov@yandex.ru>
 */
class SimPageNav
{
    protected $id;
    protected $startChar;
    protected $prevChar;
    protected $nextChar;
    protected $endChar;

    /**
     * Конструктор
     * @param string $id        - атрибут ID элемента <UL> - постраничной навигации
     * @param string $startChar - текст ссылки "В начало"
     * @param string $prevChar  - текст ссылки "Назад"
     * @param string $nextChar  - текст ссылки "Вперед"
     * @param string $endChar   - текст ссылки "В конец"
     */
    public function __construct( /*string*/ $id     = 'pagination',
        /*string*/ $startChar = '&laquo;',
        /*string*/ $prevChar  = '&lsaquo;',
        /*string*/ $nextChar  = '&rsaquo;',
        /*string*/ $endChar   = '&raquo;'  )
    {
        $this->id = $id;
        $this->startChar = $startChar;
        $this->prevChar  = $prevChar;
        $this->nextChar  = $nextChar;
        $this->endChar   = $endChar;
    }

    /**
     * Получить HTML - код постраничной навигации
     * @param int $all        - Полное кол-во элементов (Материалов в категории)
     * @param int $limit      - Кол-во элементов на странице
     * @param int $start      - Текущее смещение элементов
     * @param int $linkLimit  - Количество ссылок в состоянии
     * @param string $varName - Имя GET - переменной которая будет использоваться в постр. навигации.
     * @return string
     */
    public function getLinks( /*int*/ $all, /*int*/ $limit, /*int*/ $start, $linkLimit = 10, $varName = 'start' )
    {
        // Нихрена не делаем, если лимит больше или равен кол-ву всех элементов вообще,
        // И если лимит = 0. 0 - будет означать "не разбивать н астраницы".
        if ( $limit >= $all || $limit == 0 ) {
            return NULL;
        }

        $pages     = 0;       // кол-во страниц в пагинации
        $needChunk = 0;       // индекс нужного в данный момент чанка
        $queryVars = array(); // ассоц. массив полученный из строки запроса
        $pagesArr  = array(); // пременная для промежуточного хранения массива навигации
        $htmlOut   = '';      // HTML - код постраничной навигации
        $link      = NULL;    // формируемая ссылка

        // В этом блоке мы просто строим ссылку - такую же, как та, по которой
        // пришли на данную страницу, но извлекаем из неё нашу GET-переменную:
        parse_str($_SERVER['QUERY_STRING'], $queryVars ); //   &$queryVars

        // Убиваем нашу GET-переменную
        if( isset($queryVars[$varName]) ) {
            unset( $queryVars[$varName] );
        }

        // Формируем такую же ссылку, ведущую на эту же страницу:
        //$link  = $_SERVER['PHP_SELF'].'?'.http_build_query( $queryVars );
        $link  = '/tovar2vm/?'.http_build_query( $queryVars );
        //--------------------------------------------------------

        $pages = ceil( $all / $limit ); // кол-во страниц

        // Заполняем массив: ключ - это номер страницы, значение - это смещение для БД.
        // Нумерация здесь нужна с единицы. А смещение с шагом = кол-ву материалов на странице.
        for( $i = 0; $i < $pages; $i++) {
            $pagesArr[$i+1] = $i * $limit;
        }

        // Теперь что бы на странице отображать нужное кол-во ссылок
        // дробим массив со значениями [№ страницы] => "смещение" на
        // Части (чанки)
        $allPages = array_chunk($pagesArr, $linkLimit, true);

        // Получаем индекс чанка в котором находится нужное смещение.
        // И далее только из него сформируем список ссылок:
        $needChunk = $this->searchPage( $allPages, $start );

        // Формируем ссылки "В начало", "передыдущая" ------------------------------------------------

        if ( $start > 1 ) {
            $htmlOut .= '<li><a href="'.$link.'&'.$varName.'=0">'.$this->startChar.'</a></li>'.
                '<li><a href="'.$link.'&'.$varName.'='.($start - $limit).'">'.$this->prevChar.'</a></li>';
        } else {
            $htmlOut .= '<li><span>'.$this->startChar.'</span></li>'.
                '<li><span>'.$this->prevChar.'</span></li>';
        }
        // Собсно выводим ссылки из нужного чанка
        foreach( $allPages[$needChunk] AS $pageNum => $ofset )  {
            // Делаем текущую страницу не активной:
            if( $ofset == $start  ) {
                $htmlOut .= '<li><span>'. $pageNum .'</span></li>';
                continue;
            }
            $htmlOut .= '<li><a href="'.$link.'&'.$varName.'='. $ofset .'">'. $pageNum . '</a></li>';
        }

        // Формируем ссылки "следующая", "в конец" ------------------------------------------------

        if ( ($all - $limit) >  $start) {
            $htmlOut .= '<li><a href="' . $link . '&' . $varName . '=' . ( $start + $limit) . '">' . $this->nextChar . '</a></li>'.
                '<li><a href="' . $link . '&' . $varName . '=' . array_pop( array_pop($allPages) ) . '">' . $this->endChar . '</a></li>';
        } else {
            $htmlOut .= '<li><span>' . $this->nextChar . '</span></li>'.
                '<li><span>' . $this->endChar . '</span></li>';
        }
        return '<ul id="'.$this->id.'">' . $htmlOut . '<ul>';
    }

    /**
     * Ищет в каком чанке находится сраница со смещением $needPage
     * @param array $pagesList массив чанков (массивов страниц разбитый по лимиту ссылок на странице)
     * @param int $needPage - смещение
     * @return number Ключ чанка в котором есть нужная страница
     */
    protected function searchPage( array $pagesList, /*int*/$needPage )
    {
        foreach( $pagesList AS $chunk => $pages  ){
            if( in_array($needPage, $pages) ){
                return $chunk;
            }
        }
        return 0;
    }
}