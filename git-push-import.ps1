# PowerShell helper to initialize git repo and push to GitHub
# Run this in an elevated PowerShell terminal at C:\xampp\htdocs

Write-Host "1/ Initializing git repository..."
git init
git checkout -b import-project

Write-Host "2/ Adding files (this may take a while)..."
git add .

Write-Host "3/ Committing..."
git commit -m "chore: import project"

Write-Host "4/ Adding remote..."
git remote add origin https://github.com/Bh-34/php-final.git

Write-Host "5/ Pushing to origin/import-project (you may be prompted to authenticate)..."
git push -u origin import-project

Write-Host "Done. If push failed due to authentication, please run 'gh auth login' or configure credential helper and re-run the script."