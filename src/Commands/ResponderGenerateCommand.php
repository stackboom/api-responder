<?php
/**
 * Created by PhpStorm.
 * User: LunaticFish
 * Date: 2019/3/29
 * Time: 17:28
 */

namespace StackBoom\ApiResponder\Commands;


use Illuminate\Console\GeneratorCommand;
use StackBoom\ApiResponder\Models\ResponderModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResponderGenerateCommand extends GeneratorCommand
{
    /**
     * @var string $name
     */
    protected $name = 'api-responder:generate';

    /**
     * @var string $description
     */
    protected $description = 'Generate your own Responder Class;';

    /**
     * @var string $type
     */
    protected $type = 'Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__."/../stubs/Responder.stub";
    }

    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Responder';
    }

    protected function buildClass($name = null)
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->replaceNamespace($stub, $name)
            ->replaceMetas($stub)
            ->replaceFields($stub)
            ->replaceClass($stub, $name);
    }

    protected function replaceFields(&$stub){
        $docContent = '';

        /**
         * @var ResponderModel[] $responders
         */
        $responders = ResponderModel::all()->unique('name');

        foreach ($responders as $responder){
            $docContent .= " * @method static static {$responder->camel_name}\n";
        }

        $this->getOutput()->success($responders->count());

        $stub = str_replace("{{phpDocFields}}\n", $docContent, $stub,$count);

        return $this;
    }

    protected function replaceMetas(&$stub){
        $stub = str_replace([
            '{{Date}}',
            '{{Time}}',
        ], [
            date('Y/m/d'),
            date('H:i:s'),
        ], $stub);

        return $this;
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of the class','Responder'],
        ];
    }
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));
        return empty($name)?'Responder':$name;
    }

    protected function getOptions()
    {
        return array_merge(parent::getOptions(),[
            ['force','f',InputOption::VALUE_NONE ,'Create the class even if the model already exists'],
        ]);
    }
}