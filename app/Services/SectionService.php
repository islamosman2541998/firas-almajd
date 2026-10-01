<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Resolves page sections: schema from config/sections.php, content and
 * visibility/order from settings ("section.{page}.{key}", "layout.{page}").
 */
class SectionService
{
    public function __construct(protected SettingsService $settings) {}

    public function pages(): array
    {
        return config('sections.pages');
    }

    public function page(string $page): array
    {
        return config("sections.pages.{$page}") ?? throw new InvalidArgumentException("Unknown page [{$page}]");
    }

    /**
     * Section instances of a page as [key => ['type', 'defaults']].
     */
    public function instances(string $page): array
    {
        $instances = [];
        foreach ($this->page($page)['sections'] as $key => $definition) {
            [$type, $defaults] = is_array($definition) ? [$definition[0], $definition[1] ?? []] : [$definition, []];
            $instances[$key] = ['type' => $type, 'defaults' => $defaults];
        }

        return $instances;
    }

    public function type(string $type): array
    {
        return config("sections.types.{$type}") ?? throw new InvalidArgumentException("Unknown section type [{$type}]");
    }

    /**
     * Ordered layout rows: [['key' => .., 'enabled' => bool], ...]
     */
    public function layout(string $page): array
    {
        $keys = array_keys($this->instances($page));
        $stored = (array) $this->settings->get("layout.{$page}", []);

        $layout = [];
        foreach ($stored as $row) {
            if (in_array($row['key'] ?? null, $keys, true)) {
                $layout[$row['key']] = ['key' => $row['key'], 'enabled' => (bool) ($row['enabled'] ?? true)];
            }
        }
        // Sections added after the order was saved go right after their config predecessor.
        foreach ($keys as $i => $key) {
            if (isset($layout[$key])) {
                continue;
            }
            $row = [$key => ['key' => $key, 'enabled' => true]];
            $after = $i > 0 ? array_search($keys[$i - 1], array_keys($layout), true) : -1;
            $layout = $after === false
                ? $layout + $row
                : array_slice($layout, 0, $after + 1, true) + $row + array_slice($layout, $after + 1, null, true);
        }

        return array_values($layout);
    }

    /** Defaults for one section instance (type defaults + instance overrides). */
    public function defaults(string $page, string $key): array
    {
        $instance = $this->instances($page)[$key];
        $values = [];
        foreach ($this->type($instance['type'])['fields'] as $name => $field) {
            $values[$name] = $field['default'] ?? ($field['type'] === 'repeater' ? [] : (! empty($field['t']) ? ['ar' => '', 'en' => ''] : null));
        }

        return array_replace($values, $instance['defaults']);
    }

    public function data(string $page, string $key): array
    {
        $stored = (array) $this->settings->get("section.{$page}.{$key}", []);

        return array_replace($this->defaults($page, $key), $stored);
    }

    /**
     * Enabled sections of a page, ready for rendering.
     *
     * @return array<int, array{key: string, type: string, view: string, data: array}>
     */
    public function render(string $page): array
    {
        $instances = $this->instances($page);
        $sections = [];

        foreach ($this->layout($page) as $row) {
            if (! $row['enabled']) {
                continue;
            }
            $type = $instances[$row['key']]['type'];
            $sections[] = [
                'key' => $row['key'],
                'type' => $type,
                'view' => $this->type($type)['view'],
                'data' => $this->data($page, $row['key']),
            ];
        }

        return $sections;
    }

    public function isEnabled(string $page, string $key): bool
    {
        foreach ($this->layout($page) as $row) {
            if ($row['key'] === $key) {
                return $row['enabled'];
            }
        }

        return false;
    }

    public function save(string $page, string $key, array $data): void
    {
        $this->settings->put("section.{$page}.{$key}", $data);
    }

    public function saveLayout(string $page, array $layout): void
    {
        $this->settings->put("layout.{$page}", array_values($layout));
    }

    public function reset(string $page, string $key): void
    {
        $this->settings->forget("section.{$page}.{$key}");
    }
}
