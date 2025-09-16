#!/bin/bash

# Team Manager - Deployment Preparation Script
# This script prepares your application for deployment to Namecheap shared hosting

echo "🚀 Preparing Team Manager for Namecheap Deployment..."

# Create deployment directory
mkdir -p deployment/team-manager

# Copy all necessary files
echo "📁 Copying application files..."
cp -r config/ deployment/team-manager/
cp -r controllers/ deployment/team-manager/
cp -r core/ deployment/team-manager/
cp -r database/ deployment/team-manager/
cp -r models/ deployment/team-manager/
cp -r uploads/ deployment/team-manager/
cp -r views/ deployment/team-manager/

# Copy essential files
cp .htaccess deployment/team-manager/
cp index.php deployment/team-manager/
cp router.php deployment/team-manager/
cp database.sql deployment/team-manager/
cp README.md deployment/team-manager/

# Set proper permissions
echo "🔒 Setting file permissions..."
find deployment/team-manager -type f -exec chmod 644 {} \;
find deployment/team-manager -type d -exec chmod 755 {} \;
chmod 755 deployment/team-manager/uploads/
chmod 755 deployment/team-manager/uploads/receipts/
chmod 755 deployment/team-manager/uploads/daily-reports/

# Create archive for upload
echo "📦 Creating deployment archive..."
cd deployment
zip -r team-manager-deploy.zip team-manager/
cd ..

echo "✅ Deployment package ready: deployment/team-manager-deploy.zip"
echo "📄 Next: Follow the deployment guide to upload to Namecheap"
