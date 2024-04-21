<?php

namespace Javaabu\Paperless\Support\Components;

use Closure;
use Exception;
use Illuminate\View\View;
use Javaabu\Paperless\Support\Components\Traits\EvaluatesClosures;

abstract class Component
{
    use EvaluatesClosures;

    protected string $view;
    protected string | Closure | null $defaultView = null;
    protected array $viewData                      = [];

    /**
     * @throws Exception
     */
    public function getView(): string
    {
        $view_path = config('paperless.views');

        if (isset($this->view)) {
            if ($view_path) {
                $pos            = strpos($this->view, '::');
                $replace_string = $view_path . ".";
                $position       = $pos + 2;

                return substr_replace($this->view, $replace_string, $position, 0);
            }

            return $this->view;
        }

        if (filled($defaultView = $this->getDefaultView())) {
            return $defaultView;
        }

        throw new Exception('Class [' . static::class . '] extends [' . Component::class . '] but does not have a [$view] property defined.');
    }

    public function viewData(array $data): static
    {
        $this->viewData = [
            ...$this->viewData,
            ...$data,
        ];

        return $this;
    }

    public function defaultView(string | Closure | null $view): static
    {
        $this->defaultView = $view;

        return $this;
    }

    public function getDefaultView(): ?string
    {
        return $this->evaluate($this->defaultView);
    }

    public function toHtml(): string
    {
        return $this->render()->render();
    }

    public function extractPublicMethods(): array
    {
        $reflection     = new \ReflectionClass($this);
        $public_methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods        = [];

        foreach ($public_methods as $method) {
            $methods[$method->getName()] = \Closure::fromCallable([$this, $method->getName()]);
        }

        return $methods;
    }

    /**
     * @throws Exception
     */
    public function render(): View
    {
        return view(
            $this->getView(),
            [
                ...$this->extractPublicMethods(),
                ...$this->viewData,
            ]
        );
    }


}
