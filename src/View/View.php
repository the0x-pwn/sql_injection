<?php

namespace Src\View;

class View
{
    public static function make(string $view, array $data): void
    {
        $getBaseContent = static::BaseContent();
        $getViewContent = static::ViewContent($view, data: $data);
        echo str_replace('{{content}}', $getViewContent, $getBaseContent);
    }

    protected static function BaseContent()
    {
        ob_start();
        include view_path() . 'layout/main.php';
        return ob_get_clean();
    }

    protected static function ViewContent(string $view, bool $isError = false, array $data = [])
    {
        $path = $isError ? view_path() . 'error/' : view_path();

        if (str_contains($view, '.')) {
            $views = explode('.', $view);

            foreach ($views as $view) {
                if (is_dir($path . $view)) {
                    $path = $path . $view . '/';
                }
            }
            $view = $path . end($views) . '.php';
        } else {
            $view = $path . $view . '.php';
        }

        extract($data);

        if ($isError) {
            include $view;
        } else {
            ob_start();
            include $view;
            return ob_get_clean();
        }
    }
}
