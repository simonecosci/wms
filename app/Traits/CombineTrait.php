<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\ProductCombination;

trait CombineTrait {

    /**
     * Generate all the possible combinations among a set of nested arrays.
     *
     * @param array $data  The entrypoint array container.
     * @param array $all   The final container (used internally).
     * @param array $group The sub container (used internally).
     * @param mixed $value The value to append (used internally).
     * @param int   $i     The key index (used internally).
     */
    public function generateCombinations(array $data, array &$all = array(), array $group = array(), $value = null, $i = 0) {
        $keys = array_keys($data);
        if (isset($value) === true) {
            array_push($group, $value);
        }
        if ($i >= count($data)) {
            array_push($all, $group);
        } else {
            $currentKey = $keys[$i];
            $currentElement = $data[$currentKey];
            foreach ($currentElement as $val) {
                $this->generateCombinations($data, $all, $group, $val, $i + 1);
            }
        }
        return $all;
    }

    /**
     * 
     * @param type $product_id
     */
    public function recombine($product_id = null) {
        if (!is_null($product_id)) {
            $product = Product::find($product_id);
            if (empty($product))
                return;
        } else {
            $product = $this;
        }
        $variants = $product->getVariants();
        $combinations = $this->generateCombinations($variants);
        $comb = [];
        foreach ($combinations as $combination) {
            sort($combination);
            $comb[] = implode('-', $combination);
        }
        ProductCombination::where('product_id', $product->id)
                ->whereNotIn('combination', $comb)
                ->delete();
        foreach ($comb as $_combination) {
            $pvv = ProductCombination::where('product_id', $product->id)
                    ->where('combination', $_combination)
                    ->first();
            if (empty($pvv)) {
                (new ProductCombination)->fill([
                    'product_id' => $product->id,
                    'combination' => $_combination,
                    'quantity' => 0,
                    'block_overflow' => false,
                    'internalcode' => ''
                ])->save();
            }
        }
        return count($comb);
    }

}
