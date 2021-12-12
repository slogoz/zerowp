<?php

final class Zero_DIContainer implements Psr\Container\ContainerInterface
{
	private $definitions = [];
	private $instances = [];



	public function __construct(array $definitions = [])
    {
        $this->setMultiple($definitions);
    }



	public function set(string $id, $definition): void
    {
        if ($this->hasInstance($id)) {
            unset($this->instances[$id]);
        }

        $this->definitions[$id] = $definition;
    }


	
	public function setMultiple(array $definitions): void
    {
        foreach ($definitions as $id => $definition) {
            $this->checkIdIsStringType($id);
            $this->set($id, $definition);
        }
    }



	public function get($id)
    {
        $this->checkIdIsStringType($id);

        if ($this->hasInstance($id)) {
            return $this->instances[$id];
        }

        $this->instances[$id] = $this->getNew($id);
        return $this->instances[$id];
    }



    public function getNew(string $id)
    {
        $instance = $this->createInstance($id);

        if ($instance instanceof FactoryInterface) {
            return $instance->create($this);
        }

        return $instance;
    }



    public function getDefinition(string $id)
    {
        if ($this->has($id)) {
            return $this->definitions[$id];
        }

        throw new NotFoundException(sprintf('`%s` is not set in container.', $id));
    }



    public function has($id): bool
    {
        return array_key_exists($id, $this->definitions);
    }
	


	private function createInstance(string $id)
    {
        if (!$this->has($id)) {
            if ($this->isClassName($id)) {
                return $this->createObject($id);
            }

            throw new NotFoundException(sprintf('`%s` is not set in container and is not a class name.', $id));
        }

        if ($this->isClassName($this->definitions[$id])) {
            return $this->createObject($this->definitions[$id]);
        }

        if ($this->definitions[$id] instanceof Closure) {
            return $this->definitions[$id]($this);
        }

        return $this->definitions[$id];
    }



    private function createObject(string $className)
    {
        try {
            $reflection = new ReflectionClass($className);
        } catch (ReflectionException $e) {
            throw new ContainerException(sprintf('Unable to create object `%s`.', $className), 0, $e);
        }

        if (($constructor = $reflection->getConstructor()) === null) {
            return $reflection->newInstance();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            if ($type = $parameter->getType()) {
                $typeName = $type->getName();

                if (!$type->isBuiltin() && ($this->has($typeName) || $this->isClassName($typeName))) {
                    $arguments[] = $this->get($typeName);
                    continue;
                }

                if ($type->isBuiltin() && $typeName === 'array' && !$parameter->isDefaultValueAvailable()) {
                    $arguments[] = [];
                    continue;
                }
            }

            if ($parameter->isDefaultValueAvailable()) {
                try {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                } catch (ReflectionException $e) {
                    throw new ContainerException(sprintf(
                        'Unable to create object `%s`. Unable to get default value of constructor parameter: `%s`.',
                        $reflection->getName(),
                        $parameter->getName()
                    ));
                }
            }

            throw new Psr\Container\ContainerException(sprintf(
                'Unable to create object `%s`. Unable to process a constructor parameter: `%s`.',
                $reflection->getName(),
                $parameter->getName()
            ));
        }

        return $reflection->newInstanceArgs($arguments);
    }



    private function hasInstance(string $id): bool
    {
        return array_key_exists($id, $this->instances);
    }



    private function isClassName($className): bool
    {
        // echo '<pre>';
        // print_r($className);
        // echo PHP_EOL;
        // echo '</pre>';
        return (is_string($className) && class_exists($className));
    }



    private function checkIdIsStringType($id): void
    {
        if (!is_string($id)) {
            throw new NotFoundException(sprintf(
                'Is not valid ID. Must be string type; received `%s`.',
                gettype($id)
            ));
        }
    }
}