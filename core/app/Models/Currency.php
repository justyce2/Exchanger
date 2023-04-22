<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\Searchable;
use App\Traits\CommonScope;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model {
    use Searchable, CommonScope, GlobalStatus;

    protected $appends = [
        'final_buy_at',
        'final_sell_at'
    ];

    public function gatewayCurrency() {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function userDetailsData() {
        return $this->belongsTo(Form::class, 'user_detail_form_id');
    }
    public function transactionProvedData() {
        return $this->belongsTo(Form::class, 'trx_proof_form_id');
    }
    public function scopeAvailableForSell($query) {
        return $query->where('available_for_sell', Status::YES);
    }
    public function scopeAvailableForBuy($query) {
        return $query->where('available_for_buy', Status::YES);
    }
    public function getFinalBuyAtAttribute() {
        if ($this->buy_at <= 0) {
            return 0;
        }
        $buyAtt                    = $this->buy_at;
        $fixedCharge               = $this->fixed_charge_for_buy;
        $fixedChargeInBaseCurrency = ((1 / $buyAtt) * $fixedCharge);
        $percentCharge             = (($buyAtt / 100) * $this->percent_charge_for_buy);
        $totalBuyAtt               = $fixedChargeInBaseCurrency + $percentCharge + $buyAtt;
        return $totalBuyAtt;
    }

    public function getFinalSellAtAttribute() {
        if ($this->sell_at <= 0) {
            return 0;
        }
        $sellAtt                   = $this->sell_at;
        $fixedCharge               = $this->fixed_charge_for_sell;
        $fixedChargeInBaseCurrency = ((1 / $sellAtt) * $fixedCharge);
        $percentCharge             = (($sellAtt / 100) * $this->percent_charge_for_sell);
        $totalSellAt               = $fixedChargeInBaseCurrency + $percentCharge + $sellAtt;
        return $totalSellAt;
    }
}
