pipeline {
  agent any
  stages {
    stage('START') {
      steps {
        echo 'Docker hub login...'
      }
    }

    stage('BUILD') {
      steps {
        sh 'make build'
      }
    }

    stage('BENCH') {
      steps {
        sh 'make bench'
      }
    }

  }
}