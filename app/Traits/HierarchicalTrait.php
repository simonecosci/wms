<?php

namespace App\Traits;

trait HierarchicalTrait {
    
    protected $parent_reference = "parent_id";

    public function read($options = null) {
        return static::query()->whereNull($this->parent_reference)
                ->orderBy('index', 'ASC')
                ->get();
    }

    public static function query() {
        return parent::query()->with(['items']);
    }

    public function items() {
        return $this->hasMany(get_class($this), $this->parent_reference)
                ->with(['items'])
                ->orderBy('index', 'ASC');
    }

    public function parent() {
        return $this->belongsTo(get_class($this), $this->parent_reference)
                ->with(['parent']);
    }

    public function remove() {
        foreach ($this->items as $entry) {
            static::with('items')->find($entry->id)->remove();
        }
        return $this->delete();
    }
    
    public function render($lang, $root = null, $levels = null) {
        $items = $this->read([
            $this->parent_reference => $root,
            'visible' => 1
        ]);
        return $this->menuItems($items, $lang, $levels);
    }

    public function link($item, $lang) {
        $link = '#';
        return $link;
    }

    protected function menuItems($items, $lang, $levels = null, $level = 0) {
        $menu = '';
        foreach ($items as $item) {
            $link = $this->link($item, $lang);
            $title = $text = $item->name;
            $menu .= '<li>'
                    . '<a href="' . e($link) . '" title="' . e($title) . '">'
                    . ($text)
                    . '</a>';
            if (!empty($levels) && $levels > $level) {
                $children = $item->items()->where('visible', 1)->get();
                if ($children->count() > 0) {
                    $menu .= '<ul>';
                    $menu .= $this->menuItems($children, $lang, $levels, $level + 1);
                    $menu .= '</ul>';
                }
            }
            $menu .= '</li>';
        }
        return $menu;
    }
}
