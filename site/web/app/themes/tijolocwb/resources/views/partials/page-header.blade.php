<div class="page-header">
  <h1 class="text-5xl text-center mt-8 font-bold animate__animated animate__fadeInDown">{!! $title !!}</h1>
</div>

@php
// Somente para a categoria Dia a Dia
  $descricao_categoria = category_description();
  if (!empty($descricao_categoria)) {
      echo '<div id="categoria-descricao" class="text-center">' . $descricao_categoria . '</div>';
  }
@endphp 