<?php

namespace App\Illuminate\Custom\Database\Eloquent\Factories;

use Illuminate\Support\Str;

abstract class Factory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model|TModel>
     */
    public function modelName(): string
    {
        $resolver = static::$modelNameResolver ?? function (self $factory) {
            $namespacedFactoryBasename = Str::replaceLast(
                'Factory', '', Str::replaceFirst(static::$namespace, '', get_class($factory))
            );

            $factoryBasename = Str::replaceLast('Factory', '', class_basename($factory));

            $appNamespace = static::appNamespace();

            return class_exists($appNamespace.'Entities\\'.$namespacedFactoryBasename)
                ? $appNamespace.'Entities\\'.$namespacedFactoryBasename
                : $appNamespace.$factoryBasename;
        };

        return $this->model ?? $resolver($this);
    }

    /**
     * Get the factory name for the given model name.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelName
     * @return class-string<\Illuminate\Database\Eloquent\Factories\Factory>
     */
    public static function resolveFactoryName(string $modelName): string
    {
        $resolver = static::$factoryNameResolver ?? function (string $modelName) {
            $appNamespace = static::appNamespace();
            $modelName = Str::startsWith($modelName, $appNamespace.'Entities\\')
                ? Str::after($modelName, $appNamespace.'Entities\\')
                : Str::after($modelName, $appNamespace);

            return static::$namespace.$modelName.'Factory';
        };

        return $resolver($modelName);
    }
}
