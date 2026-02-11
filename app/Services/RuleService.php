<?php

namespace App\Services;

use App\Models\Rule;

class RuleService
{
    public function all()
    {
        return Rule::orderBy('rule_name')->get();
    }

    public function find($id)
    {
        return Rule::find($id);
    }

    public function save($id, $name, $value)
    {
        return Rule::updateOrCreate(
            ['id' => $id],
            [
                'rule_name' => $name,
                'rule_value' => $value,
            ]
        );
    }

    public function delete($id)
    {
        Rule::find($id)?->delete();
    }

    public static function can($ruleName)
    {
        $user = auth()->user();
        if (!$user) return false;

        $rule = Rule::where('rule_name', $ruleName)->first();
        if (!$rule) return false;

        $roles = explode(',', $rule->rule_value);

        return in_array($user->role, $roles);
    }
}
