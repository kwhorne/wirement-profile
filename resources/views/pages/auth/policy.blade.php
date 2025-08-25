<x-filament-panels::page.simple>
{{Str::markdown(file_get_contents(\Wirement\Profile\WirementProfile::plugin()?->policyMarkdown))}}
</x-filament-panels::page.simple>
