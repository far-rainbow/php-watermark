pipeline {
  agent any
  stages {
    stage('START') {
      steps {
        echo 'TEST MSG'
      }
    }

    stage('BUILD & BENCH') {
      steps {
        sh 'make php-watermark'
      }
    }

  }
}