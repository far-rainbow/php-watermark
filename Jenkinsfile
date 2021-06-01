pipeline {
  agent any
  stages {
    stage('START') {
      steps {
        echo 'Docker hub login...'
      }
    }

    stage('BUILD & BENCH') {
      steps {
        sh 'make php-watermark'
      }
    }

  }
}