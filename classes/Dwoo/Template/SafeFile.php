<?php
class Dwoo_Template_SafeFile extends Dwoo_Template_File
{
    protected function getCompiledFilename(Dwoo $dwoo)
    {
        // no compile id was provided, set default
        if ($this->compileId===null) {
            $this->compileId = hash('md4', $this->getResourceIdentifier());
        }
        return $dwoo->getCompileDir() . $this->compileId.'.d'.Dwoo::RELEASE_TAG.'.php';
    }
}
?>
