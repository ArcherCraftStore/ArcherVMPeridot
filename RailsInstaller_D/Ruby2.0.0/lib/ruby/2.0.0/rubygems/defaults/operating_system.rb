# :DK-BEG: override 'gem install' to enable RubyInstaller DevKit usage
Gem.pre_install do |gem_installer|
  unless gem_installer.spec.extensions.empty?
    unless ENV['PATH'].include?('E:\\xampp\\RailsInstaller_D\\DevKit\\mingw\\bin') then
      Gem.ui.say 'Temporarily enhancing PATH to include DevKit...' if Gem.configuration.verbose
      ENV['PATH'] = 'E:\\xampp\\RailsInstaller_D\\DevKit\\bin;E:\\xampp\\RailsInstaller_D\\DevKit\\mingw\\bin;' + ENV['PATH']
    end
    ENV['RI_DEVKIT'] = 'E:\\xampp\\RailsInstaller_D\\DevKit'
    ENV['CC'] = 'gcc'
    ENV['CXX'] = 'g++'
    ENV['CPP'] = 'cpp'
  end
end
# :DK-END:
