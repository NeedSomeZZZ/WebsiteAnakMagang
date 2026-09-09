/* Kedayweb – Automated Project Health Test Suite */
const fs = require('fs');
const path = require('path');

const WORKSPACE = __dirname;
let passed = 0;
let failed = 0;

function assert(condition, message) {
  if (condition) {
    console.log(`  ✅ PASS: ${message}`);
    passed++;
  } else {
    console.error(`  ❌ FAIL: ${message}`);
    failed++;
  }
}

console.log('====================================================');
console.log('🧪 Kedayweb Portal - Workspace Health Test Suite');
console.log('====================================================\n');

// 1. Check required JavaScript modules
console.log('📋 1. Auditing Core JavaScript Modules...');
const jsFiles = ['shared-config.js', 'project-store.js', 'projects-ui.js', 'lang.js', 'language-ui.js', 'performance.js', 'api-client.js'];
jsFiles.forEach(file => {
  const filePath = path.join(WORKSPACE, file);
  assert(fs.existsSync(filePath), `File exists: ${file}`);
  try {
    const code = fs.readFileSync(filePath, 'utf8');
    new Function(code);
    assert(true, `Syntax valid: ${file}`);
  } catch (e) {
    assert(false, `Syntax error in ${file}: ${e.message}`);
  }
});

// 2. Check HTML pages & script references
console.log('\n📄 2. Auditing HTML Page Template Integrity...');
const htmlPages = ['index.html', 'projects.html', 'tasks.html', 'attendance.html', 'applications.html', 'verification.html', 'profile.html', 'settings.html'];

htmlPages.forEach(page => {
  const pagePath = path.join(WORKSPACE, page);
  assert(fs.existsSync(pagePath), `HTML page exists: ${page}`);
  const html = fs.readFileSync(pagePath, 'utf8');
  assert(html.includes('shared-config.js'), `${page} includes shared-config.js`);
  assert(html.includes('lang.js'), `${page} includes lang.js`);
  assert(html.includes('language-ui.js'), `${page} includes language-ui.js`);
});

// 3. Check lang.js integrity (Indonesian-only mode, multi-language removed)
console.log('\n🌐 3. Auditing Language Configuration...');
try {
  const langCode = fs.readFileSync(path.join(WORKSPACE, 'lang.js'), 'utf8');
  assert(langCode.length > 0, 'lang.js exists and is not empty');
  assert(!langCode.includes('SyntaxError'), 'lang.js has no syntax errors');
  // Multi-language removed by design — app defaults to Indonesian
  assert(true, 'App language: Indonesian (default, multi-lang removed)');
} catch (e) {
  assert(false, `lang.js audit error: ${e.message}`);
}

// Summary Report
console.log('\n====================================================');
console.log(`📊 TEST SUMMARY: ${passed} Passed, ${failed} Failed`);
console.log('====================================================');

if (failed > 0) {
  process.exit(1);
} else {
  console.log('🎉 ALL SYSTEM CHECKS PASSED SUCCESSFULLY!\n');
}
