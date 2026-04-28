@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify')
  <h1 class="h4 mb-3">Configurações do Sistema</h1>
    <div class="glass-card">
        <form action="{{ route('admin.configuracoes.update') }}" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        
            @csrf

            <h5 class="mt-3">Geral</h5>
            <input name="nome_sistema" type="text" class="form-control text-light mb-2" required value="<?php echo $config->nome_sistema; ?>" required>
            <h5 class="mt-4">Contato</h5>
            <input name="email_contato" type="email" class="form-control text-light mb-2" value="<?php echo $config->email_contato; ?>" placeholder="Email" >
            <input name="telefone_sac" type="tel" class="form-control text-light mb-2" value="<?php echo $config->telefone_sac; ?>" placeholder="Telefone SAC (DDD + Número)" maxlenght="18">
            <input name="telefone_whatsapp" type="tel" class="form-control text-light mb-2" value="<?php echo $config->telefone_whatsapp; ?>" placeholder="Whatsapp (DDD + Número)" maxlenght="18">

            <h5 class="mt-4">Identidade visual</h5>    
            <?php
                $logoPath = (!empty($config->logo)) ? $config->logo  : 'img/config/default.png';
                $logoUrl = asset($logoPath);

                $faviconPath = (!empty($config->favicon)) ? $config->favicon  : 'img/config/default.png';
                $faviconUrl  = $faviconPath ? asset($faviconPath) : '';

                $emailHeaderPath = (!empty($config->email_header_image)) ? $config->email_header_image  : 'img/config/default.png';
                $emailHeaderUrl  = $emailHeaderPath ? asset($emailHeaderPath) : '';
            ?>    
            <div class="mb-4 mt-4">
                <label class="form-label">Logo do sistema</label>
                <input type="hidden" name="logo_current" value="<?php echo $logoPath; ?>">
                <div class="input-group">
                    <input type="file"
                        name="logo_file"
                        class="form-control"
                        accept=".png,.jpg,.jpeg,.webp">

                    @if ($logoUrl)
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#modallogo">
                        <i class="bi bi-eye"></i>
                    </button>
                    @endif
                </div>
                {{-- mini preview --}}
                @if ($logoUrl)
                    <div class="mt-2">
                        <img
                            src="<?php echo $logoUrl; ?>"
                            alt="Miniatura"
                            style="max-height:70px;border:1px solid #ddd;border-radius:6px;" 
                            loading="lazy">
                    </div>
                @endif
            </div>

            <div class="mb-4 mt-4">
                <label class="form-label">Favicon</label>
                <input type="hidden" name="favicon_current" value="<?php echo $faviconPath; ?>">
                <div class="input-group">
                    <input type="file"
                        name="favicon_file"
                        class="form-control"
                        accept=".png,.jpg,.jpeg,.webp">

                    @if ($faviconUrl)
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalfavicon">
                        <i class="bi bi-eye"></i>
                    </button>
                    @endif
                </div>
                {{-- mini preview --}}
                @if ($faviconUrl)
                    <div class="mt-2">
                        <img
                            src="<?php echo $faviconUrl; ?>"
                            alt="Miniatura"
                            style="max-height:70px;border:1px solid #ddd;border-radius:6px;" 
                            loading="lazy">
                @endif
            </div>

            <div class="mb-4 mt-4">
                <label class="form-label">Cabeçalho de email</label>
                <input type="hidden" name="email_header_current" value="<?php echo $emailHeaderPath; ?>">
                <div class="input-group">
                    <input type="file"
                        name="email_header_file"
                        class="form-control"
                        accept=".png,.jpg,.jpeg,.webp">

                    @if ($emailHeaderUrl)
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalemailheader">
                        <i class="bi bi-eye"></i>
                    </button>
                    @endif
                </div>
                {{-- mini preview --}}
                @if ($emailHeaderUrl)
                    <div class="mt-2 mb-2">
                        <img
                            src="<?php echo $emailHeaderUrl; ?>"
                            alt="Miniatura"
                            style="max-height:70px;border:1px solid #ddd;border-radius:6px;" 
                            loading="lazy">
                @endif
            </div>
            
            <input type="email" name="email_smtp" class="form-control my-2" value="<?php echo $config->email_smtp; ?>" placeholder="Email SMTP (para respostas automáticas de emails)" >

            <h5 class="mt-4">Redes sociais</h5>
            <input name="link_facebook" type="url" class="form-control text-light mb-2" value="<?php echo $config->link_facebook; ?>" placeholder="Facebook" >
            <input name="link_instagram" type="url" class="form-control text-light mb-2" value="<?php echo $config->link_instagram; ?>" placeholder="Instagram" >
            <input name="link_linkedin" type="url" class="form-control text-light mb-2" value="<?php echo $config->link_linkedin; ?>" placeholder="Linkedin" >
            <input name="link_youtube" type="url" class="form-control text-light mb-2" value="<?php echo $config->link_youtube; ?>" placeholder="Youtube" >

            <h5 class="mt-4">Endereço</h5>
            <input name="endereco" type="text" class="form-control text-light mb-2" value="<?php echo $config->endereco; ?>" placeholder="Endereço">
            <input name="horario_atendimento" type="text" class="form-control text-light mb-2" value="<?php echo $config->horario_atendimento; ?>" placeholder="Horário de atendimento">

            <h5 class="mt-4">Mapa (iframe do google maps)</h5>
            <textarea name="mapa_iframe" rows="4" class="form-control text-light">
                <?php echo $config->mapa_iframe; ?>
            </textarea>

            <button class="btn btn-primary mt-4">
            <i class="bi bi-save me-1"></i>Salvar configurações
            </button>
        </form>
    </div>
</div>


{{-- Modal --}}
@if ($logoUrl)
    <div class="modal fade" id="modallogo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">           
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Visualizar imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                        <img
                        src="<?php echo $logoUrl; ?>"
                        alt="Miniatura"
                        style="max-width:100%;"
                        loading="lazy">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($faviconUrl)
    <div class="modal fade" id="modalfavicon" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
           <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Visualizar imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                        <img
                        src="<?php echo $faviconUrl; ?>"
                        alt="Miniatura"
                        style="max-width:100%;"
                        loading="lazy">
                </div>
            </div>
        </div>
    </div>
@endif

@if ($emailHeaderUrl)
    <div class="modal fade" id="modalemailheader" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">        
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Visualizar imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body text-center">
                        <img
                        src="<?php echo $emailHeaderUrl; ?>"
                        alt="Miniatura"
                        style="max-width:100%;"
                        loading="lazy">
                </div>
            </div>
        </div>
    </div>
@endif



@include('admin.partials.footer')
@include('admin.partials.scripts')