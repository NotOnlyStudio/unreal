<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="unreal.shop/assets/logo.png" class="logo" alt="UnrealShop">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
