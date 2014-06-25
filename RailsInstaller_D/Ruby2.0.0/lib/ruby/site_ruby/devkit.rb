# enable RubyInstaller DevKit usage as a vendorable helper library
unless ENV['PATH'].include?('E:\\xampp\\RailsInstaller_D\\DevKit\\mingw\\bin') then
  puts 'Temporarily enhancing PATH to include DevKit...'
  ENV['PATH'] = 'E:\\xampp\\RailsInstaller_D\\DevKit\\bin;E:\\xampp\\RailsInstaller_D\\DevKit\\mingw\\bin;' + ENV['PATH']
end
ENV['RI_DEVKIT'] = 'E:\\xampp\\RailsInstaller_D\\DevKit'
ENV['CC'] = 'gcc'
ENV['CXX'] = 'g++'
ENV['CPP'] = 'cpp'
