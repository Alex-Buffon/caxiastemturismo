# 🚀 Guia de Deploy no GitHub

## Pré-requisitos

1. **Instalar o Git**
   - Download: https://git-scm.com/download/win
   - Durante instalação, marque "Git from the command line and also from 3rd-party software"
   - Após instalação, reinicie o VS Code

2. **Criar conta no GitHub** (se não tiver)
   - Acesse: https://github.com/signup

## 📝 Passo a Passo

### 1️⃣ Configurar Git (primeira vez apenas)

Abra o terminal e execute:

```bash
git config --global user.name "Seu Nome"
git config --global user.email "seu-email@example.com"
```

### 2️⃣ Inicializar Repositório Local

```bash
cd "e:\site caxiastemturismo"
git init
git add .
git commit -m "Initial commit: Site Caxias Tem Turismo"
```

### 3️⃣ Criar Repositório no GitHub

1. Acesse: https://github.com/new
2. Repository name: `caxiastemturismo` (ou outro nome)
3. Description: "Site promocional de turismo rural em Caxias do Sul/RS"
4. **Público** ou **Privado** (sua escolha)
5. ❌ **NÃO** marque "Add a README file" (já temos um)
6. Clique em **"Create repository"**

### 4️⃣ Conectar e Enviar para GitHub

Após criar o repositório, copie os comandos que aparecem na tela ou use:

```bash
git remote add origin https://github.com/SEU-USUARIO/caxiastemturismo.git
git branch -M main
git push -u origin main
```

**Substitua `SEU-USUARIO`** pelo seu nome de usuário do GitHub!

### 5️⃣ Ativar GitHub Pages

1. No GitHub, vá no seu repositório
2. Clique em **Settings** (⚙️)
3. No menu lateral, clique em **Pages**
4. Em "Source", selecione:
   - Branch: `main`
   - Folder: `/ (root)`
5. Clique em **Save**
6. Aguarde 1-2 minutos
7. O site estará disponível em: `https://SEU-USUARIO.github.io/caxiastemturismo`

## 🔄 Atualizações Futuras

Quando fizer alterações no site:

```bash
git add .
git commit -m "Descrição das mudanças"
git push
```

O GitHub Pages atualizará automaticamente!

## ⚠️ Importante: Atualizar URLs

Após deploy, substitua em **TODAS as páginas**:

```html
<!-- DE: -->
<link rel="canonical" href="https://www.seusite.com/...">
<meta property="og:url" content="https://www.seusite.com/...">

<!-- PARA: -->
<link rel="canonical" href="https://SEU-USUARIO.github.io/caxiastemturismo/...">
<meta property="og:url" content="https://SEU-USUARIO.github.io/caxiastemturismo/...">
```

Ou use um domínio customizado (veja documentação do GitHub Pages).

## 🆘 Problemas Comuns

### Git não reconhecido
- Reinstale o Git e reinicie o computador
- Verifique se está no PATH: `echo $env:PATH` (PowerShell)

### Autenticação no GitHub
- Use Personal Access Token ao invés de senha
- Gere em: Settings → Developer settings → Personal access tokens → Tokens (classic)
- Permissões necessárias: `repo`

### Página não carrega imagens
- Verifique se todas as imagens estão commitadas: `git status`
- Confira caminhos relativos nas subpastas (`../img/`)

## 📚 Recursos

- [Documentação GitHub Pages](https://docs.github.com/pt/pages)
- [Git Básico](https://git-scm.com/book/pt-br/v2)
- [Domínio Customizado](https://docs.github.com/pt/pages/configuring-a-custom-domain-for-your-github-pages-site)
