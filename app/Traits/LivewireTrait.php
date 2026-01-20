<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

trait LivewireTrait {

    protected $arrayFiles;

    public function applyTrim($properties) {

        foreach ($properties as $property) {

            $array = explode('.', $property);

            if (count($array) > 1) {

                if (is_string($this->{$array[0]}->{$array[1]}) || is_int($this->{$array[0]}->{$array[1]})) {

                    $this->{$array[0]}->{$array[1]} = trim($this->{$array[0]}->{$array[1]});

                }

            }else{

                if (is_string($this->{$property}) || is_int($this->{$property})) {
                    $this->{$property} = trim($this->{$property});
                }
            }
        }
    }

    public function nullProperties($properties){

        foreach ($properties as $property) {
            $array = explode('.', $property);
            if (count($array) > 1) {
                $this->{$array[0]}->{$array[1]} = $this->{$array[0]}->{$array[1]} === "" ? null : $this->{$array[0]}->{$array[1]};
            }else{
                $this->{$property} = $this->{$property} === "" ? null : $this->{$property};
            }
        }

    }

    /**
     * Valida si los archivos que se van a guardar estan subidos en la carpeta temporal de livewire
     * NOTA: Se ha observado que algunos archivos con extensión .php .exe etc.. Se suben pero de inmediato se borran.
     * la unica forma que encontre es validar el mime y si arroja una excepción significa que el archivo no se encuentra en la carpeta temporal
     * intente con otros metodos como file_exists() o file->exists() de livewire me arrojaban true por eso descarte estos metodos.
     */
    public function filesIsValid(Array $files) : bool{
        foreach ($files as $item) {
            try {
                $item->getMimeType();
            } catch (\Throwable $th) {
                return false;
            }
        }
        return true;
    }

    public function storeFiles($model, $files, $path){

        $this->arrayFiles=[];

        foreach ($files as $file) {

            $url = $file->store($path);

            if($url !== false){
                $model->resources()->create([
                    'url' => $url,
                    'name' => $file->getClientOriginalName(),
                    'extension' => $file->extension(),
                ]);
                $this->arrayFiles[] = $url;
            }else{
                $this->deleteFiles();
                return false;
            }
        }
        return true;
    }

    public function deleteFiles(){
        if (is_array($this->arrayFiles)) {
            foreach ($this->arrayFiles as $file) {
                Storage::delete($file);
            }
        }
    }

    public function getMB($files=[], $files2=[]){
        $mb = 0;
        foreach ($files as $file) {
            $mb = $mb + $file->getSize();
        }

        foreach ($files2 as $file) {
            $mb = $mb + $file->getSize();
        }

        $mb = $mb / 1048576;


        return  bcdiv($mb, '1', 2);
    }

    public function validateRecaptcha($token){

        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($cu, CURLOPT_POST, 1);
        curl_setopt($cu, CURLOPT_POSTFIELDS, http_build_query(array('secret' => config('services.recaptcha.secret'), 'response' => $token)));
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, true);
        $respose = curl_exec($cu);

        curl_close($cu);

        $datos = json_decode($respose, true);

        $validator = Validator::make($datos, [
            'success' => 'required|accepted',
            'hostname' => [ 'required', Rule::in(['crea.test', 'creamostutesis.com', 'plataforma.crea.test', 'plataforma.creamostutesis.com'])],
            'score' => 'required|numeric|min:0.10',
        ]);

        return $validator;
    }

    /**
     * Valida si el usuario tiene asignada una sede, si no cuenta con una sede emite una alerta
     */
    protected function validateTerminal(){
        if (!hasTerminal()) {
            $this->emit('error', 'El usuario no tiene una sede asignada');
            throw ValidationException::withMessages([]);
        }
    }
}
