<?php

namespace MerapiPanel\Box {

    use Symfony\Component\Filesystem\Path;


    class FileLoader
    {

        public readonly string $directory;

        public function __construct(string $directory)
        {
            $this->directory = $directory;
        }

        public function getDirectory()
        {
            return $this->directory;
        }

        function getFile(string $name): FileFragment|null
        {
            if (file_exists(Path::join($this->directory, $name))) {
                return new FileFragment(Path::join($this->directory, $name));
            }
            return null;
        }
    }





    class FileFragment
    {

        protected string $path;
        protected string $name;
        protected string $basename;
        protected string|null $content;
        protected string $extension;


        public function __construct(string $path)
        {


            $this->path = $path;
            $this->name = pathinfo($path, PATHINFO_FILENAME);
            $this->basename = pathinfo($path, PATHINFO_BASENAME);
            $this->extension = pathinfo($path, PATHINFO_EXTENSION);
            if (file_exists($path)) {
                $this->content = file_get_contents($path);
            }

        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getPath(): string
        {
            return $this->path;
        }

        public function getContent(): string
        {
            return $this->content;
        }

        public function getExtension(): string
        {
            return $this->extension;
        }


        public function getLastModified(): int
        {

            return filemtime($this->path);
        }

        public function setName(string $name)
        {

            $this->name = $name;
        }

        public function setPath(string $path)
        {
            $this->path = $path;
        }

        public function setContent(string $content)
        {
            $this->content = $content;
        }

        public function setExtension(string $extension)
        {
            $this->extension = $extension;
        }

        public function save(int $flags = 0)
        {

            try {
                file_put_contents($this->basename, $this->content, $flags);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }


        public function __toString()
        {
            return $this->path;
        }
    }

}