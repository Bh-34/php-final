# php-final

Monorepo com backend (Laravel) e frontend (Vue 3 + Vite + TypeScript + Vuetify).

Estrutura relevante:
- php-final-back/compra-cafe — Laravel backend
- php-final-front/compra-cafe — Vue frontend

Pré-requisitos para rodar localmente (Windows):
- PHP 8.2, Composer, MySQL (XAMPP)
- Node.js, npm, yarn (opcional)
- Git e GitHub CLI (`gh`) para push/PR

Como preparar e testar localmente:
1. Backend:
   - cd php-final-back/compra-cafe
   - copy .env.example .env
   - ajustar variáveis de DB em .env
   - composer install
   - php artisan migrate --force
   - php artisan serve --host=127.0.0.1 --port=8001

2. Frontend:
   - cd php-final-front/compra-cafe
   - npm install
   - npm run dev -- --host
   - acesse http://localhost:5173

Processo de commit / push (execute no terminal na raiz `C:\xampp\htdocs`):
- git init
- git checkout -b import-project
- git add .
- git commit -m "chore: import project"
- git remote add origin https://github.com/Bh-34/php-final.git
- git push -u origin import-project

Observações:
- Não comite segredos. `.env` já está em `.gitignore` nas pastas. Se houver segredos comitados anteriormente, precisaremos limpar o histórico.
- Posso adicionar um GitHub Actions básico para CI se quiser (teste + lint + build).
