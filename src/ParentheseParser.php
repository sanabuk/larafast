<?php
namespace sanabuk\larafast;

class ParentheseParser
{
    public $depht;
    public $previous_models;
    public $output;

    public function generate($string, $open = '(', $close = ')')
    {
        $len_string = strlen($string);
        $tokens     = [];
        for ($i = 0; $i < $len_string; $i++) {
            $tokens[] = $string[$i];
        }

        $naming                = '';
        $this->previous_models = [];
        $this->output          = [];
        $this->depht           = 0;

        foreach ($tokens as $key => $value) {
            if ($key == $len_string - 1) {
                switch ($value) {
                    case $close:
                        if ($naming != '') {
                            $this->output = $this->addConstrains($naming);
                            $naming       = '';
                        }
                        array_pop($this->previous_models);
                        break;

                    default:
                        $naming .= $value;
                        $this->output = $this->addConstrains($naming);
                        $naming       = '';
                        break;
                }
                return $this->output;
            }
            switch ($value) {
                case $open:
                    $this->output            = $this->createDepht($naming);
                    $this->previous_models[] = $naming;
                    $naming                  = '';
                    $this->depht++;
                    break;

                case ',':
                    if ($naming == '') {
                        break;
                    }
                    $this->output = $this->addConstrains($naming);
                    $naming       = '';
                    break;

                case $close:
                    if ($naming != '') {
                        $this->output = $this->addConstrains($naming);
                        $naming       = '';
                    }
                    array_pop($this->previous_models);
                    break;

                default:
                    $naming .= $value;
                    break;
            }
        }

        return $this->output;
    }

    private function createDepht($string)
    {
        switch (count($this->previous_models)) {
            case 0:
                $this->output[$string] = [];
                break;

            case 1:
                $this->output[$this->previous_models[0]][$string] = [];
                break;

            case 2:
                $this->output[$this->previous_models[0]][$this->previous_models[1]][$string] = [];
                break;

            case 3:
                $this->output[$this->previous_models[0]][$this->previous_models[1]][$this->previous_models[2]][$string] = [];
                break;

            default:
                # code...
                break;
        }
        return $this->output;
    }

    private function addConstrains($string)
    {
        switch (count($this->previous_models)) {
            case 1:
                $this->output[$this->previous_models[0]][] = $string;
                break;

            case 2:
                $this->output[$this->previous_models[0]][$this->previous_models[1]][] = $string;
                break;

            case 3:
                $this->output[$this->previous_models[0]][$this->previous_models[1]][$this->previous_models[2]][] = $string;
                break;

            case 4:
                $this->output[$this->previous_models[0]][$this->previous_models[1]][$this->previous_models[2]][$this->previous_models[3]][] = $string;
                break;

            case 0:
                $this->output[] = $string;
                break;

            default:
                $this->output[] = $string;
                break;
        }
        return $this->output;
    }

}
