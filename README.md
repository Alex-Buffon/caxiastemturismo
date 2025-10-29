# Caxias Tem Turismo

Site promocional de turismo para Caxias do Sul/RS, focado em destinos rurais e de interior da Serra Gaúcha.

## 🌄 Sobre o Projeto

Portal de turismo dedicado a promover os encantos do interior de Caxias do Sul, destacando:
- Turismo rural e agroturismo
- Gastronomia típica italiana
- Patrimônio histórico e cultural
- Belezas naturais da Serra Gaúcha

## 🚀 Tecnologias

- HTML5 semântico com Schema.org markup
- CSS3 com variáveis customizadas
- Bootstrap 5.3.3
- JavaScript vanilla
- Google Analytics 4
- Service Worker para cache offline
- PWA-ready

## 📁 Estrutura do Projeto

```
/
├── index.html              # Página principal
├── css/
│   ├── style.css          # Estilos globais
│   └── counter.css        # Contador de visitas
├── js/
│   ├── analytics.js       # Google Analytics
│   └── sw.js              # Service Worker
├── img/                   # Imagens dos destinos
├── destinos/              # Páginas de destinos turísticos
│   ├── santa-lucia.html
│   ├── fazenda-souza.html
│   ├── terceira-legua.html
│   ├── galopolis.html
│   └── turismo-religioso.html
└── servicos/              # Páginas de prestadores de serviço
    ├── agencias-de-turismo.html
    ├── agentes-de-turismo.html
    └── transportadores-turisticos.html
```

## 🎨 Design System

### Cores
- **Primary**: `#2E4636` - Verde escuro elegante
- **Accent**: `#A14C3A` - Terracota/vinho quente
- **Light**: `#F9F6F2` - Creme suave
- **Dark**: `#333333` - Cinza escuro

### Tipografia
- **Headings**: Montserrat 700
- **Body**: Lato 400/700

## 🌐 Deploy

Este é um site estático que pode ser hospedado em:
- GitHub Pages
- Netlify
- Vercel
- Qualquer servidor web (Apache, Nginx)

### GitHub Pages

1. Vá em Settings → Pages
2. Source: Deploy from a branch
3. Branch: `main` / `(root)`
4. O site estará disponível em `https://[seu-usuario].github.io/[nome-do-repo]`

## 📝 SEO

O site implementa:
- Meta tags otimizadas (description, keywords)
- Open Graph para redes sociais
- Twitter Cards
- Schema.org markup para conteúdo estruturado
- URLs canônicas
- Lazy loading de imagens
- Sitemap-ready

## 🔧 Como Adicionar Novos Destinos

1. Copie `destinos/santa-lucia.html` como template
2. Atualize meta tags (title, description, keywords, og:image)
3. Adicione a imagem em `/img/` (padrão: `img.[nome-destino].png`)
4. Atualize o dropdown de navegação em todas as páginas
5. Adicione card na home (`index.html` seção `#destinos`)
6. Se for destino principal, adicione ao hero carousel

## 📞 Contato

WhatsApp: [+55 54 98122-2284](https://wa.me/5554981222284)

## 📄 Licença

© 2025 Caxias Tem Turismo. Todos os direitos reservados.

---

**Desenvolvido para promover o turismo sustentável e a cultura local de Caxias do Sul** 🍇
