<div class="page-header mb-12">
  <h1 class="text-5xl text-center mt-8">{!! $title !!}</h1>
</div>

@php
// Somente para a categoria Dia a Dia
  $descricao_categoria = category_description();
  if (!empty($descricao_categoria)) {
      echo '<div id="categoria-descricao" class="text-center">' . $descricao_categoria . '</div>';
  }
@endphp 