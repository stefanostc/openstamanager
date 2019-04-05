<?php

namespace Common\Components;

use Illuminate\Database\Eloquent\Builder;

abstract class Discount extends Row
{
    public function getIvaAttribute()
    {
        return $this->attributes['iva'];
    }

    /**
     * Effettua i conti per l'IVA.
     */
    protected function fixIva()
    {
        $this->attributes['iva'] = parent::getIvaAttribute();

        $descrizione = $this->aliquota->descrizione;
        if (!empty($descrizione)) {
            $this->attributes['desc_iva'] = $descrizione;
        }

        $this->fixIvaIndetraibile();
    }

    protected static function boot($bypass = false)
    {
        parent::boot(true);

        static::addGlobalScope('discounts', function (Builder $builder) {
            $builder->where('subtotale', '=', 0);
        });
    }
}