<?php

namespace app;

class Renderer
{
    private string $directory;
    private ?string $extend;
    private string $blockName;
    private array $blocks = [];
    private array $filters = [
        'escape' => 'htmlspecialchars',
        'striptags' => 'strip_tags',
    ];

    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * Метод возвращает шаблон с html-разметкой и внедернными с нее параметрами.
     * В случае если текущий шаблон расширяется ($this->extend != null) другим шаблоном,
     * метод рекурсивно вызывает сам себя до тех пор, пока не получит в шаблон без расширения
     * (например, базовая разметка).
     *
     * @param string $template
     * @param array $parameters
     * @return string
     */
    public function render(string $template, array $parameters = []): string
    {
        $template = $this->directory . '/' . $template;
        $this->extend = null;

        extract($parameters);

        ob_start();
        include $template;
        $content = ob_get_clean();

        if (!is_null($this->extend)) {
            $content = $this->render($this->extend);
        }

        return $content;
    }

    /**
     * Метод вызывается внутри шаблона при необходимости разметисть имеющуюуся в нем разметку в другой шаблон.
     *
     * @param string $template
     */
    public function extends(string $template): void
    {
        $this->extend = $template;
    }

    /**
     * Метод указывает на начало блока с html-разметкой в шаблоне,
     * запускает буфферизацию идущей после него разметки.
     *
     * @param string $name
     */
    public function block(string $name): void
    {
        $this->blockName = $name;
        ob_start();
    }

    /**
     * Метод указывает на конец блока с html-разметкой в шаблоне.
     * При наличии в массиве $this->blocks разметки с аналогичным указанному блоку
     * выводит ее в шаблон в противном случае сохраняет его в массив
     * (во избежание переопределения блока другим блоком с аналогичнм именем в последующих шаблонах).
     * При отсутствии в массиве $this->blocks кода с именем текущего блока, а также отсутствии расширений
     * выводит содержимое блока в шаблон.
     *
     * @param string $name
     */
    public function endBlock()
    {
        $blockContent = ob_get_clean();

        if (array_key_exists($this->blockName, $this->blocks)) {
            echo $this->blocks[$this->blockName];
        } elseif (is_null($this->extend)) {
            echo $blockContent;
        } else {
            $this->blocks[$this->blockName] = $blockContent;
        }
    }

    /**
     * метод выводит в шаблон переменную, прменяя к ней заданные фильтры
     *
     * @param $parameter
     * @param mixed ...$filters
     */
    public function get($parameter, ...$filters)
    {
        foreach ($filters as $filter) {
            if (array_key_exists($filter, $this->filters)) {
                $parameter = $this->filters[$filter]($parameter);
            }
        }

        echo $parameter;
    }
}
