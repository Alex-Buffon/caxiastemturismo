# Copilot Instructions - Caxias Tem Turismo

## Visão Geral do Projeto
Site estático de turismo promocional para Caxias do Sul/RS focado em destinos rurais e de interior da Serra Gaúcha. Público-alvo: turistas brasileiros interessados em turismo rural, gastronomia italiana e cultura colonial.

## Arquitetura e Estrutura

### Estrutura de Pastas
```
/                       # Raiz: index.html (página principal)
├── css/               # Estilos globais
├── js/                # Analytics e Service Worker
├── img/               # Imagens dos destinos (*.png)
├── destinos/          # Páginas de destinos turísticos
└── servicos/          # Páginas de prestadores de serviço
```

### Padrão de Navegação Multi-Nível
- **Raiz**: `index.html` é o hub principal com hero carousel rotativo
- **Subpastas**: Todas as páginas em `/destinos/` e `/servicos/` usam caminhos relativos (`../`) para assets
- **URLs**: Links internos usam hashes (#inicio, #roteiros, #destinos, #contato) na home; páginas de destino navegam de volta com `../index.html#section`

## Convenções de Código

### HTML - Estrutura Semântica e SEO
Todas as páginas seguem um padrão rígido de SEO otimizado:

```html
<!-- Meta tags obrigatórias em TODAS as páginas -->
<meta name="description" content="[Descrição específica da página]">
<meta name="keywords" content="[palavras-chave, separadas, por vírgula]">
<link rel="canonical" href="https://www.seusite.com/[caminho-da-página]">
<meta property="og:*"> <!-- Open Graph para Facebook -->
<meta name="twitter:*"> <!-- Twitter Cards -->
```

**Schema.org Markup**: Use `itemscope itemtype` nos cards:
- Destinos turísticos: `http://schema.org/TouristAttraction`
- Agências: `http://schema.org/TravelAgency`

**Performance**: Sempre incluir:
- `loading="lazy"` em imagens (exceto hero que usa `fetchpriority="high"`)
- `rel="noopener"` em links `target="_blank"`
- Preconnect para CDNs externos (Bootstrap, Google Fonts)

### CSS - Design System
**Variáveis CSS** definidas em `css/style.css`:
```css
--primary: #2E4636;    /* Verde escuro elegante */
--accent: #A14C3A;     /* Terracota/vinho quente */
--light: #F9F6F2;      /* Creme suave */
--dark: #333333;       /* Texto cinza escuro */
```

**Padrão de Cards**:
- `.card-img-overlay-wrapper`: Container de altura fixa (220px)
- Overlay com gradiente escuro no bottom (0 → 85% opacidade)
- Hover: Escala imagem (1.05x), remove line-clamp do texto
- Transições: `cubic-bezier(0.165, 0.84, 0.44, 1)` para suavidade

**Componentes Críticos**:
- Hero carousel: 6 slides com backgrounds específicos (`.bg-img-1` a `.bg-img-6`)
- WhatsApp float button: Fixo bottom-right com animação `@keyframes pulse`
- Visitor counter: Estilo glassmorphism em `counter.css`

### JavaScript

**Analytics** (`js/analytics.js`):
- GA4 tracking (ID: `G-BRXRGBF2S6`)
- Eventos automáticos:
  - `click_destino`: Links contendo `destinos/`
  - `click_external`: Links externos ou `target="_blank"`

**Service Worker** (`js/sw.js`):
- Cache-first strategy para assets estáticos
- Cache name: `caxias-turismo-v1` (incrementar em atualizações)
- Assets críticos: index.html, CSS, principais imagens, Bootstrap CDN

**Registro do SW**: Todo HTML tem inline script para registrar `/js/sw.js` (ajustar path em subpastas: `../js/sw.js`)

## Workflows de Desenvolvimento

### Adicionar Novo Destino
1. Criar HTML em `/destinos/[nome-do-destino].html`
2. Copiar estrutura de `santa-lucia.html` (template padrão)
3. Ajustar meta tags (title, description, keywords, og:image)
4. Atualizar dropdowns de navegação em TODAS as páginas:
   ```html
   <li><a class="dropdown-item" href="[caminho]">[Nome]</a></li>
   ```
5. Adicionar card na home (`index.html` seção `#destinos`)
6. Incluir imagem em `/img/` (formato: `img.[nome-destino].png`)
7. Atualizar hero carousel se for destino principal (adicionar `.bg-img-X` em CSS)

### Modificar Estilos
- Estilos globais: `css/style.css` (CSS comments com padrão `/*-- <--- Section Name --> -*/`)
- NÃO editar CDN do Bootstrap - customizações via CSS vars ou classes específicas
- Testar responsividade: componentes críticos têm media queries em `@media (max-width: 768px)` e `480px`

### Atualizar Service Worker
Quando modificar assets cacheados:
1. Incrementar `CACHE_NAME` em `js/sw.js` (ex: `caxias-turismo-v2`)
2. Atualizar array `ASSETS_TO_CACHE` se adicionou/removeu arquivos
3. Testar limpeza de cache antigo via DevTools

## Integrações Externas

### Bootstrap 5.3.3
- CDN via jsDelivr (CSS + JS bundle)
- Componentes usados: Navbar, Carousel, Cards, Dropdown, Forms
- Grid responsivo: `col-md-4` para cards de 3 colunas

### Google Fonts
- Montserrat 700 (headings)
- Lato 400/700 (body text)

### WhatsApp Business
Número fixo: `5554981222284`
- Mensagem pré-formatada em todos os botões flutuantes
- Ajustar texto do parâmetro `text=` se mudar chamada para ação

### Visitor Counter
API externa (freecounterstat.com) - mesmo contador em todas as páginas via parâmetro `c=eeffwb4qu8lj5pdz8ygld1p9rkhx59tu`

## Padrões Específicos do Projeto

### Imagens
- Formato PNG prioritário
- Naming: `img.[destino-ou-funcao].png` (ex: `img.santalucia.png`)
- Hero principal: `img1.png`
- Não há lazy loading no hero (usa `fetchpriority="high"`)

### Formulários
- HTML5 validation (`required` attributes)
- Sem backend - formulários prontos para integração futura
- Classes Bootstrap: `.form-control`, `.form-label`, `.mb-3`

### Acessibilidade
- `aria-label` em todos os ícones/botões sem texto visível
- `aria-expanded` nos dropdowns
- `alt` descritivo em todas as imagens

## Notas de Manutenção

- **URL canônica**: Atualmente aponta para `https://www.seusite.com` - substituir pelo domínio final
- **Placeholders**: Cards de exemplo em páginas de serviço aguardam conteúdo real
- **Links**: Google Maps genéricos - substituir por URLs específicas dos estabelecimentos
- **Forms**: Sem ação - implementar backend ou serviço de email (ex: Formspree)

## Quando Criar Novos Arquivos
- Novas páginas de destino: Sempre usar template de `destinos/santa-lucia.html`
- CSS adicional: Criar arquivos separados e importar no `<head>` após `style.css`
- JS features: Adicionar inline em `<script>` antes de `</body>` ou criar módulo separado com `defer`
