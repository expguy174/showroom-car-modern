<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class UniqueAddress implements Rule
{
    protected $excludeId;

    public function __construct($excludeId = null)
    {
        $this->excludeId = $excludeId;
    }

    public function passes($attribute, $value)
    {
        $query = Address::where('user_id', Auth::id())
            ->where('address', request('address'))
            ->where('state', request('state'))
            ->where('city', request('city'));

        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return 'Địa chỉ này đã tồn tại trong sổ địa chỉ của bạn.';
    }
}
